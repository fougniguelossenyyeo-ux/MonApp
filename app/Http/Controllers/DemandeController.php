<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Entite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Mail;
use App\Mail\NouvelleDemandeDP; // ou DemandePaiementMail selon le nom que tu as donné
use App\Mail\NotificationDAF;
use App\Mail\NotificationDG;
use App\Mail\NotificationTresorie;
use App\Mail\DemandeRefusee;
use App\Mail\DemandeRefuseeDaf;
use App\Mail\DemandeRefuseeDG;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
class DemandeController extends Controller
{

private function verifierPermission($user, $demande, $niveau)
{
    // Transformer le nom de l'entité en slug
    $slugEntite = Str::slug($demande->entite->libelle_entite, '_');

    // Construire le nom de permission
    $permissionName = "valider_demande_niveau{$niveau}_{$slugEntite}";
   

    // Vérifier si l'utilisateur possède cette permission
    return $user->role
        ->permissions
        ->where('nom', $permissionName)
        ->isNotEmpty();
}
// envoie de mail vers les utilisateurs du niveau de validation suivant
private function envoyerNotificationNiveau($demande, $niveau, $cc = [])
{
    $slugEntite = Str::slug($demande->entite->libelle_entite, '_');

    $permission = "valider_demande_niveau{$niveau}_{$slugEntite}";

    $utilisateurs = User::whereHas('role.permissions', function ($q) use ($permission) {
        $q->where('nom', $permission);
    })->get();

    // Ajouter l'initiateur dans les CC
    if ($demande->user) {
        $cc[] = $demande->user->email;
    }

    foreach ($utilisateurs as $utilisateur) {

        if ($niveau == 2) {
            Mail::to($utilisateur->email)
                ->cc($cc)
                ->send(new NotificationDAF($demande));
        }

        if ($niveau == 3) {
            Mail::to($utilisateur->email)
                ->cc($cc)
                ->send(new NotificationDG($demande));
        }
    }
}

    /**
     * Liste toutes les demandes
     */
public function index()
{
    // Récupérer toutes les demandes, peu importe le statut
    $demandes = Demande::with(['entite', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate(12); // Pagination

    // Calcul des totaux pour le dashboard (somme des montants)
    $totalDemandes = Demande::whereIn('status', [0,1,2,3])->sum('montant_paiement_fournisseur');
    $totalEnAttenteControleur = Demande::where('status', 0)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDaf = Demande::where('status', 1)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDirecteur = Demande::where('status', 2)->sum('montant_paiement_fournisseur');
    $totalValide = Demande::where('status', 3)->sum('montant_paiement_fournisseur');

    // Taux de traitement
    $tauxTraitement = $totalDemandes > 0 
        ? round(($totalValide / $totalDemandes) * 100, 2) 
        : 0;

    return view('demandes.index', compact(
        'demandes',
        'totalDemandes',
        'totalEnAttenteControleur',
        'totalEnAttenteDaf',
        'totalEnAttenteDirecteur',
        'totalValide',
        'tauxTraitement'
    ));
}


    /**
     * Formulaire de création
     */
public function create()
{
    $user = Auth::user();

    $permissions = $user->role->permissions;

    $permissionsCreate = $permissions->filter(function ($permission) {
        return str_starts_with($permission->nom, 'cree_demande_');
    });

    $entiteSlugs = $permissionsCreate->map(function ($permission) {
        return str_replace('cree_demande_', '', $permission->nom);
    });

    $entites = Entite::all()->filter(function ($entite) use ($entiteSlugs) {
        return $entiteSlugs->contains(Str::slug($entite->libelle_entite, '_'));
    })->values();

    return view('demandes.create', compact('entites'));
}
    /**
     * Enregistrer une demande
     */

public function store(Request $request)
{
    try {
        $tvaOptions = [
            'TVA 18%' => 18,
            'TVA 0%' => 0,
            'TVA sur hydrocarbure 9%' => 9,
        ];

        //  Validation des champs
        $validated = $request->validate([
            'denomination' => 'required|string|max:255',
            'entite_id' => 'required|uuid|exists:entites,id',
            'montant_ht' => 'required|numeric|min:0',
            'tva' => 'required|string|in:' . implode(',', array_keys($tvaOptions)),
            'contact_fournisseur' => 'required|string|max:20',
            'adresse_fournisseur' => 'required|string',
            'email_fournisseur' => 'required|email',
            'reference_facture' => 'nullable|string|max:255',
            'reference_bon_commande' => 'nullable|string|max:255',
            'reference_contrat' => 'nullable|string|max:255',
            'reference_expression_besoin' => 'nullable|string|max:255',
            'code_fournisseur' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'code_analytique' => 'nullable|string|max:255',
            'centre_analytique' => 'nullable|string|max:255',
            'code_projet' => 'nullable|string|max:255',
            'pieces_jointes.*' => 'nullable|file|mimes:pdf|max:102400',
        ]);

        $entite = Entite::findOrFail($validated['entite_id']);
        $slugEntite = Str::slug($entite->libelle_entite, '_');
        $permissionName = 'valider_demande_niveau1_' . $slugEntite;

        //  Récupération des validateurs
        $validateurs = User::with(['role.permissions'])
            ->whereHas('role', function ($query) use ($entite) {
                $query->where('entite_id', $entite->id);
            })
            ->whereHas('role.permissions', function($query) use ($permissionName) {
                $query->where('nom', $permissionName);
            })
            ->get();

        if ($validateurs->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Impossible de créer la demande : aucun utilisateur n'a la permission '$permissionName' pour l'entité '{$entite->libelle_entite}'.");
        }

        // Calcul du montant TTC
        $montantHT = $validated['montant_ht'];
        $tvaRate = $tvaOptions[$validated['tva']];
        $validated['montant_paiement_fournisseur'] = $montantHT + ($montantHT * $tvaRate / 100);

        //  Génération de la référence DP
        $validated['reference_dp'] = 'DP-CI' . date('dmY') . '-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $validated['user_id'] = auth()->id();
        $validated['status'] = 0;

        //  Gestion des fichiers joints par dossier spécifique
        if ($request->hasFile('pieces_jointes')) {
            $folder = 'pieces_jointes/' . $validated['reference_dp'];
            $storedFiles = [];

            foreach ($request->file('pieces_jointes') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs($folder, $filename, 'public'); // stockage public
                $storedFiles[] = $path;
            }

            $validated['pieces_jointes'] = json_encode($storedFiles); // on stocke un JSON des chemins
        }

        //  Création de la demande
        $demande = \App\Models\Demande::create($validated);

        //  Envoi du mail aux validateurs
        foreach ($validateurs as $user) {
            Mail::to($user->email)->send(
                new \App\Mail\NouvelleDemandeDP($demande, $user)
            );
        }

        return redirect()->route('demandes.create')
            ->with('success', "Demande {$demande->reference_dp} - {$demande->denomination} créée avec succès et notifiée aux validateurs !");

    } catch (\Exception $e) {
        \Log::error('Erreur lors de la création de la demande DP : ' . $e->getMessage());
        return redirect()->route('demandes.create')
            ->with('error', 'Une erreur est survenue lors de la création de la demande. Veuillez réessayer.');
    }
}
  
    /**

 * Affiche une demande
 */
public function show(Demande $demande)
{
    $demande->load(['entite','user']);

    $pieces = [];

    if ($demande->pieces_jointes) {
        $pieces = json_decode($demande->pieces_jointes, true);
    }

    return view('demandes.show', compact('demande','pieces'));
}
    /**
     * en attente controlleur
     */
   
public function enattenteControl()
{
    $demandes = Demande::where('status', 0)
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    // Calculs pour le dashboard
    $totalDemandes = Demande::whereIn('status', [0,1,2,3])->sum('montant_paiement_fournisseur');
    $totalEnAttenteControleur = Demande::where('status', 0)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDaf = Demande::where('status', 1)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDirecteur = Demande::where('status', 2)->sum('montant_paiement_fournisseur');
    $totalValide = Demande::where('status', 3)->sum('montant_paiement_fournisseur');

    // Taux de traitement (exemple simple)
    $tauxTraitement = $totalDemandes > 0 
        ? round(($totalValide / $totalDemandes) * 100, 2) 
        : 0;

    return view('demandes.controleur', compact(
        'demandes',
        'totalDemandes',
        'totalEnAttenteControleur',
        'totalEnAttenteDaf',
        'totalEnAttenteDirecteur',
        'totalValide',
        'tauxTraitement'
    ));
}

public function showEnAttenteControl($id)
{
    $demande = Demande::with(['entite','user'])
        ->where('id', $id)
        ->where('status', 0)
        ->firstOrFail();

    $user = Auth::user();

    // Construire le nom de permission selon l'entité

    // Vérifier permission niveau 1
    if (!$this->verifierPermission($user, $demande, 1)) {
        abort(403, "Vous n'avez pas l'autorisation de voir cette demande.");
    }

    // Décoder les pièces jointes
    $pieces = $demande->pieces_jointes 
        ? json_decode($demande->pieces_jointes, true) 
        : [];

    return view('demandes.show_enattente', compact('demande','pieces'));
}

public function validerControleur($id)
{
    $demande = Demande::with('entite', 'user')->findOrFail($id);

    $user = Auth::user();

    if ($demande->status != 0) {
        return redirect()->back()->with('error', 'Cette demande ne peut plus être validée.');
    }

    if (!$this->verifierPermission($user, $demande, 1)) {
        abort(403, "Vous n'avez pas la permission de valider cette demande.");
    }

    $demande->status = 1;
    $demande->date_validation_controleur = now();
    $demande->save();

    // Envoyer notification niveau 2
    $this->envoyerNotificationNiveau($demande, 2);

    return redirect()->route('demandes.enAttenteControl')
                     ->with('success', 'Demande envoyée au niveau suivant.');
}



public function refuserControleur($id)
{
    $demande = Demande::with('user', 'entite')->findOrFail($id);
    $user = Auth::user();

    // Vérifier si le statut est valide pour refus
    if ($demande->status != 0) {
        return redirect()->back()->with('error', 'Cette demande ne peut plus être refusée.');
    }

    // Vérifier si l'utilisateur a la permission de refuser (niveau 1)
    if (!$this->verifierPermission($user, $demande, 1)) {
        abort(403, "Vous n'avez pas la permission de refuser cette demande.");
    }

    // Mettre à jour le statut pour refus
    $demande->status = -1; // Refusé par le contrôleur
    $demande->date_validation_controleur = now();
    $demande->save();

    // Envoyer un mail à l'initiateur
    if ($demande->user && $demande->user->email) {
        Mail::to($demande->user->email)
            ->send(new DemandeRefusee($demande));
    }

    return redirect()->route('demandes.enAttenteControl')
                     ->with('error', 'La demande a été refusée et l’initiateur a été notifié.');
}
// DemandeController.php

public function enAttenteDaf()
{
    // Liste paginée des demandes en attente DAF
    $demandes = Demande::with(['entite', 'user'])
        ->where('status', 1) // status 1 = en attente DAF
        ->orderByDesc('created_at')
        ->paginate(12);

    // Calculs pour le dashboard
    $totalDemandes = Demande::whereIn('status', [0,1,2,3])->sum('montant_paiement_fournisseur');
    $totalEnAttenteControleur = Demande::where('status', 0)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDaf = Demande::where('status', 1)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDirecteur = Demande::where('status', 2)->sum('montant_paiement_fournisseur');
    $totalValide = Demande::where('status', 3)->sum('montant_paiement_fournisseur');

    // Taux de traitement (exemple simple)
    $tauxTraitement = $totalDemandes > 0 
        ? round(($totalValide / $totalDemandes) * 100, 2) 
        : 0;

    // Vue LISTE avec dashboard
    return view('demandes.daf', compact(
        'demandes',
        'totalDemandes',
        'totalEnAttenteControleur',
        'totalEnAttenteDaf',
        'totalEnAttenteDirecteur',
        'totalValide',
        'tauxTraitement'
    ));
}


public function showEnAttenteDaf($id)
{
    // Récupérer la demande en attente du DAF
    $demande = Demande::with(['entite', 'user'])
                      ->where('id', $id)
                      ->where('status', 1)
                      ->firstOrFail();
  $user = Auth::user();

    
 // Vérifier permission niveau 1
    if (!$this->verifierPermission($user, $demande, 2)) {
        abort(403, "Vous n'avez pas l'autorisation de voir cette demande.");
    }

    // Décoder les pièces jointes
    $pieces = $demande->pieces_jointes 
        ? json_decode($demande->pieces_jointes, true) 
        : [];

    return view('demandes.show_enAttenteDaf', compact('demande','pieces'));
}

//validation du DAF
public function validerDaf($id)
{ 
   
    $demande = Demande::with('entite', 'user')->findOrFail($id);
    $user = Auth::user();

    // Vérifier statut
    if ($demande->status != 1) {
        return redirect()->route('demandes.enAttenteDaf')
                         ->with('error', 'Impossible de valider cette demande.');
    }

    //  Vérifier permission (IMPORTANT)
    if (!$this->verifierPermission($user, $demande, 2)) {
        abort(403, "Vous n'avez pas la permission.");
    }

    // Mise à jour
    $demande->status = 2;
    $demande->date_validation_daf = now();
    $demande->save();

    // CC
    $cc = [];

    if ($demande->user && $demande->user->email) {
        $cc[] = $demande->user->email;
    }

    $slug = Str::slug($demande->entite->libelle_entite, '_');

    $controleur = User::whereHas('role.permissions', function ($q) use ($slug) {
        $q->where('nom', "valider_demande_niveau1_{$slug}");
    })->first();

    if ($controleur && $controleur->email) {
        $cc[] = $controleur->email;
    }
// Envoyer notification niveau 3 (DG) avec CC
    $this->envoyerNotificationNiveau($demande, 3, $cc);

    return redirect()->route('demandes.enAttenteDaf')
                     ->with('success', 'Demande validée et envoyée au niveau suivant.');
}

public function refuserDaf($id)
{
    $demande = Demande::with('user', 'entite')->findOrFail($id);
    $user = Auth::user();

    // Vérifier si le statut est valide pour refus (niveau DAF)
    if ($demande->status != 1) {
        return redirect()->back()->with('error', 'Cette demande ne peut plus être refusée.');
    }

    // Vérifier permission niveau 2
    if (!$this->verifierPermission($user, $demande, 2)) {
        abort(403, "Vous n'avez pas la permission de refuser cette demande.");
    }

    // Mettre à jour le statut
    $demande->status = -2;
    $demande->date_validation_daf = now();
    $demande->save();

    //  Construire le slug de l'entité
    $slug = Str::slug($demande->entite->libelle_entite, '_');

    //  Récupérer utilisateurs niveau 1 (contrôleurs)
    $niveau1 = User::whereHas('role.permissions', function ($q) use ($slug) {
        $q->where('nom', "valider_demande_niveau1_{$slug}");
    })->pluck('email')->toArray();

    //  Nettoyer les emails (pas de doublons / null)
    $cc = array_filter(array_unique($niveau1));

    //  Envoyer mail à l'initiateur avec CC
    if ($demande->user && $demande->user->email) {
        Mail::to($demande->user->email)
            ->cc($cc)
            ->send(new DemandeRefuseeDaf($demande));
    }

    return redirect()->route('demandes.enAttenteDaf')
                     ->with('error', 'La demande a été refusée et les acteurs ont été notifiés.');
}
// Liste des demandes en attente Directeur
public function enAttenteDirecteur()
{
    // Liste paginée des demandes en attente Directeur
    $demandes = Demande::with(['entite', 'user'])
        ->where('status', 2) // status 2 = en attente Directeur
        ->orderByDesc('created_at')
        ->paginate(12);

    // Calculs pour le dashboard
    $totalDemandes = Demande::whereIn('status', [0,1,2,3])->sum('montant_paiement_fournisseur');
    $totalEnAttenteControleur = Demande::where('status', 0)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDaf = Demande::where('status', 1)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDirecteur = Demande::where('status', 2)->sum('montant_paiement_fournisseur');
    $totalValide = Demande::where('status', 3)->sum('montant_paiement_fournisseur');

    // Taux de traitement (exemple simple)
    $tauxTraitement = $totalDemandes > 0 
        ? round(($totalValide / $totalDemandes) * 100, 2) 
        : 0;

    // Retour de la vue avec dashboard
    return view('demandes.directeur', compact(
        'demandes',
        'totalDemandes',
        'totalEnAttenteControleur',
        'totalEnAttenteDaf',
        'totalEnAttenteDirecteur',
        'totalValide',
        'tauxTraitement'
    ));
}


// Détail d'une demande en attente Directeur
public function showEnAttenteDirecteur($id)
{
    $demande = Demande::with(['entite', 'user'])
        ->where('id', $id)
        ->where('status', 2)
        ->firstOrFail();
  $user = Auth::user();

  

     // Vérifier permission niveau 1
    if (!$this->verifierPermission($user, $demande, 3)) {
        abort(403, "Vous n'avez pas l'autorisation de voir cette demande.");
    }

    // Décoder les pièces jointes
    $pieces = $demande->pieces_jointes 
        ? json_decode($demande->pieces_jointes, true) 
        : [];

    return view('demandes.show_enattente_directeur', compact('demande','pieces'));
}
// Valider la demande par le Directeur
public function validerDirecteur($id)
{
    $demande = Demande::with('entite', 'user')->findOrFail($id);

    if ($demande->status != 2) {
        return redirect()->route('demandes.enAttenteDirecteur')
                         ->with('error', 'Impossible de valider cette demande.');
    }

    // Mise à jour du statut et date validation DG
    $demande->status = 3; 
    $demande->date_validation_dg = now();
    $demande->save();

    // Créer le paiement (une seule fois)
    $demande->paiement()->create([
        'user_id' => auth()->id(),
        'montant_prevu' => $demande->montant_paiement_fournisseur,
        'montant_paye' => 0,
        'montant_restant' => $demande->montant_paiement_fournisseur,
        'statut' => 'en_attente', // non payé
    ]);

    // Préparer les CC : initiateur + DAF + contrôleur
    $cc = [$demande->user->email];

    $slug = Str::slug($demande->entite->libelle_entite, '_');

    $daf = User::whereHas('role.permissions', function ($q) use ($slug) {
        $q->where('nom', "valider_demande_niveau2_{$slug}");
    })->first();
    if ($daf) $cc[] = $daf->email;

    $controleur = User::whereHas('role.permissions', function ($q) use ($slug) {
        $q->where('nom', "valider_demande_niveau1_{$slug}");
    })->first();
    if ($controleur) $cc[] = $controleur->email;

    // Notification niveau 3
    $this->envoyerNotificationNiveau($demande, 3, $cc);

    return redirect()->route('demandes.enAttenteDirecteur')
                     ->with('success', 'Demande validée, paiement créé et notifications envoyées.');
}
// Refuser la demande par le Directeur



public function refuserDirecteur($id)
{
    $demande = Demande::with('user', 'entite')->findOrFail($id);
    $user = Auth::user();

    // Vérifier si le statut est valide pour refus (niveau DG)
    if ($demande->status != 2) {
        return redirect()->back()->with('error', 'Cette demande ne peut plus être refusée.');
    }

    // Vérifier permission niveau 3
    if (!$this->verifierPermission($user, $demande, 3)) {
        abort(403, "Vous n'avez pas la permission de refuser cette demande.");
    }

    // Mettre à jour le statut
    $demande->status = -3;
    $demande->date_validation_dg = now();
    $demande->save();

    //  Construire le slug de l'entité
    $slug = Str::slug($demande->entite->libelle_entite, '_');

    //  Récupérer utilisateurs niveau 1 et niveau 2
    $niveau1 = User::whereHas('role.permissions', function ($q) use ($slug) {
        $q->where('nom', "valider_demande_niveau1_{$slug}");
    })->pluck('email')->toArray();

    $niveau2 = User::whereHas('role.permissions', function ($q) use ($slug) {
        $q->where('nom', "valider_demande_niveau2_{$slug}");
    })->pluck('email')->toArray();

    //  Fusionner les CC
    $cc = array_filter(array_unique(array_merge($niveau1, $niveau2)));

    //  Envoyer mail à l'initiateur avec CC
    if ($demande->user && $demande->user->email) {
        Mail::to($demande->user->email)
            ->cc($cc)
            ->send(new DemandeRefuseeDG($demande));
    }

    return redirect()->route('demandes.enAttenteDirecteur')
                     ->with('error', 'La demande a été refusée et les acteurs ont été notifiés.');
}


// Afficher les demandes validées par le DG
public function valider()
{
    // Récupère toutes les demandes validées (status = 3)
    $demandes = Demande::where('status', 3)
                        ->orderByDesc('created_at')
                        ->paginate(12);

    // Calculs pour le dashboard
    $totalDemandes = Demande::whereIn('status', [0,1,2,3])->sum('montant_paiement_fournisseur');
    $totalEnAttenteControleur = Demande::where('status', 0)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDaf = Demande::where('status', 1)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDirecteur = Demande::where('status', 2)->sum('montant_paiement_fournisseur');
    $totalValide = Demande::where('status', 3)->sum('montant_paiement_fournisseur');

    // Taux de traitement
    $tauxTraitement = $totalDemandes > 0 
        ? round(($totalValide / $totalDemandes) * 100, 2) 
        : 0;

    // Retourne la vue avec le dashboard
    return view('demandes.valider', compact(
        'demandes',
        'totalDemandes',
        'totalEnAttenteControleur',
        'totalEnAttenteDaf',
        'totalEnAttenteDirecteur',
        'totalValide',
        'tauxTraitement'
    ));
}

public function showValider($id)
{
    // 1️ Récupérer la demande avec relations
    $demande = Demande::with(['entite', 'user'])
        ->where('id', $id)
        ->where('status', 3) //  uniquement les demandes validées
        ->firstOrFail();

    $user = Auth::user();

    // 2️ Vérifier la permission (niveau 3 = DG)
    if (!$this->verifierPermission($user, $demande, 3)) {
        abort(403, "Vous n'avez pas l'autorisation de voir cette demande.");
    }

    // 3️ Décoder les pièces jointes
    $pieces = $demande->pieces_jointes 
        ? json_decode($demande->pieces_jointes, true) 
        : [];

    // 4️ Retourner la vue
    return view('demandes.show_valider', compact('demande', 'pieces'));
}
//impression demandes de paiements
public function imprimer($id)
{
    $demande = Demande::with('user', 'entite')->findOrFail($id);

    // Affiche la vue HTML pour impression
    return view('demandes.dp_imprimer', compact('demande'));
}



}
