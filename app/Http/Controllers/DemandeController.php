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
class DemandeController extends Controller
{
    /**
     * Liste toutes les demandes
     */
 public function index()
{
    // 1️ Récupération des demandes avec relations
    $demandes = Demande::with(['entite', 'user'])
        ->orderByDesc('created_at')
        ->paginate(12); // Pagination 12 par page

    // 2️ Calcul des totaux par statut (hors refusés)
    $totalDemandes = Demande::whereIn('status', [0,1,2,3])->sum('montant_paiement_fournisseur');
    $totalEnAttenteControleur = Demande::where('status', 0)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDaf = Demande::where('status', 1)->sum('montant_paiement_fournisseur');
    $totalEnAttenteDirecteur = Demande::where('status', 2)->sum('montant_paiement_fournisseur');
    $totalValide = Demande::where('status', 3)->sum('montant_paiement_fournisseur');

    // 3️ Calcul du taux de traitement
    $tauxTraitement = $totalDemandes > 0
        ? round(($totalValide / $totalDemandes) * 100, 2)
        : 0;

    // 4️ Retour de la vue avec toutes les variables pour le dashboard
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
        $entites = Entite::all();
        $users = User::all();
        return view('demandes.create', compact('entites', 'users'));
    }

    /**
     * Enregistrer une demande
     */
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'denomination' => 'required|string|max:255',
            'entite_id' => 'required|uuid|exists:entites,id',
            'nom_fournisseur' => 'required|string|max:255',
            'montant_ht' => 'required|numeric|min:0',
            'tva' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
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
            'priorite' => 'nullable|string|in:normal,urgent,tres_urgent',
            'pieces_jointes.*' => 'nullable|file|mimes:pdf|max:102400',
        ]);

        // Calcul du montant TTC
        $montantHT = $validated['montant_ht'];
        $tva = $validated['tva'];
        $validated['montant_paiement_fournisseur'] = $montantHT + ($montantHT * $tva / 100);

        // Génération du code DP
        $validated['reference_dp'] = 'DP-CI' . date('dmY') . '-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $validated['user_id'] = auth()->id();
        $validated['status'] = 0;

        // Gestion des fichiers joints (FPDI)
        if ($request->hasFile('pieces_jointes')) {
            $denomination = preg_replace('/[^A-Za-z0-9\-]/', '_', $request->denomination);
            $date = date('d-m-Y');
            $mergedFileName = 'pieces_jointes/' . $denomination . '_' . $date . '.pdf';
            $mergedFilePath = storage_path('app/public/' . $mergedFileName);

            $pdf = new \setasign\Fpdi\Fpdi();
            foreach ($request->file('pieces_jointes') as $file) {
                $pageCount = $pdf->setSourceFile($file->getPathname());
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $tpl = $pdf->importPage($pageNo);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);
                }
            }
            $pdf->Output($mergedFilePath, 'F');
            $validated['pieces_jointes'] = $mergedFileName;

            // Libération mémoire
            $pdf = null;
        }

        // Création de la demande
        $demande = Demande::create($validated);

        // Notification aux contrôleurs de l'entité
        $controleurs = User::whereHas('role.entite', function($q) use ($demande) {
            $q->where('libelle_entite', $demande->entite->libelle_entite);
        })->whereHas('role', function($q){
            $q->whereRaw('LOWER(libelle) = ?', ['controleur']);
        })->get();

        foreach ($controleurs as $user) {
            Mail::to($user->email)
                ->send(new NouvelleDemandeDp($demande, $controleurs));
        }

        return redirect()->route('demandes.create')
                         ->with('success', "Demande {$demande->reference_dp} - {$demande->denomination} créée avec succès !");
        
    } catch (\Exception $e) {
        // Log de l'erreur pour le debug
        \Log::error('Erreur lors de la création de la demande DP : ' . $e->getMessage());

        return redirect()->route('demandes.create')
                         ->with('error', 'Une erreur est survenue lors de la création de la demande. Veuillez réessayer.');
    }
}
  
    /**
v/**
 * Affiche une demande
 */
public function show(Demande $demande)
{
    // Charger les relations
    $demande->load(['entite', 'user']);

    // Comme on enregistre un seul PDF fusionné, inutile de décoder en JSON
    $piece = $demande->pieces_jointes ? asset('storage/' . $demande->pieces_jointes) : null;

    return view('demandes.show', compact('demande', 'piece'));
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
    // On récupère uniquement une demande avec statut = 0
    $demande = Demande::where('status', 0)->findOrFail($id);

    return view('demandes.show_enattente', compact('demande'));
}

public function validerControleur($id)
{
    $demande = Demande::with('entite', 'user')->find($id); // Charger l'entité et l'utilisateur qui a initié

    if ($demande && $demande->status == 0) {
        $demande->status = 1; // Passe en attente DAF
        $demande->date_validation_controleur = now();
        $demande->save();

        // Récupérer les DAF de l'entité
        $dafs = User::whereHas('role', function ($q) use ($demande) {
                $q->whereRaw('LOWER(libelle) = ?', ['daf'])
                  ->where('entite_id', $demande->entite_id); // L'entité est dans le role
            })
            ->get();

        // Envoi d'un email à chaque DAF de l'entité, en mettant l'initiateur en copie
        foreach ($dafs as $daf) {
            Mail::to($daf->email)
                ->cc($demande->user->email) // copie à l'initiateur
                ->send(new NotificationDAF($demande));
        }

        return redirect()->route('demandes.enAttenteControl')
                         ->with('success', "Demande validée et envoyée aux DAF de l’entité.");
    }

    return redirect()->route('demandes.enAttenteControl')
                     ->with('error', 'Impossible de valider cette demande.');
}



public function refuserControleur($id)
{
    // Récupérer la demande avec l'utilisateur qui l'a initiée
    $demande = Demande::with('user')->findOrFail($id);

    // Mettre le statut à -1 (refusé par le contrôleur)
    $demande->status = -1;
    $demande->date_validation_controleur = now(); 
    $demande->save();

    // Envoyer un email à l'initiateur
    if ($demande->user && $demande->user->email) {
        Mail::to($demande->user->email)
            ->send(new DemandeRefusee($demande));
    }

    // Rediriger avec message d'erreur
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
    // Une seule demande, détail
    $demande = Demande::with(['entite', 'user'])
                      ->where('id', $id)
                      ->where('status', 1)
                      ->firstOrFail();

    // Vue DÉTAIL
    return view('demandes.show_enattenteDaf', compact('demande'));
}

//validation du DAF
public function validerDAF($id)
{
    $demande = Demande::with('entite', 'user')->find($id); // Charger entité et utilisateur

    if ($demande && $demande->status == 1) {
        $demande->status = 2; // En attente DG
        $demande->date_validation_daf = now();
        $demande->save();

        // Récupérer les DG de l'entité
        $dgs = User::whereHas('role', function ($q) use ($demande) {
                $q->whereRaw('LOWER(libelle) = ?', ['dg'])
                  ->where('entite_id', $demande->entite_id);
            })
            ->get();

        // Récupérer le contrôleur de la même entité
        $controleur = User::whereHas('role', function($q) use ($demande) {
            $q->whereRaw('LOWER(libelle) = ?', ['controleur'])
              ->where('entite_id', $demande->entite_id);
        })->first();

        // Préparer la liste des CC
        $cc = [];
        if ($demande->user) $cc[] = $demande->user->email;       // Initiateur
        if ($controleur) $cc[] = $controleur->email;            // Contrôleur

        // Envoyer le mail à tous les DG de l’entité
        if ($dgs->isNotEmpty()) {
            Mail::to($dgs->pluck('email'))
                ->cc($cc)
                ->send(new NotificationDG($demande));
        }

        return redirect()->route('demandes.enAttenteDaf')
                         ->with('success', 'Demande validée et envoyée aux DG de l’entité ');
    }

    return redirect()->route('demandes.enAttenteDaf')
                     ->with('error', 'Impossible de valider cette demande.');
}

public function refuserDaf($id)
{
    $demande = Demande::with('entite', 'user')->findOrFail($id); // Charger l'entité et l'utilisateur
    if($demande && $demande->status == 1){
        $demande->status = -2; // Refusée par DAF
         $demande->date_validation_daf = now();
        $demande->save();

        // Récupérer le contrôleur de l'entité
        $controleur = User::whereHas('role', function($q) use ($demande) {
            $q->where('entite_id', $demande->entite_id)
              ->whereRaw('LOWER(libelle) = ?', ['controleur']);
        })->first();

        // Envoi du mail à l'initiateur avec le contrôleur en copie
        if($demande->user && $controleur) {
            Mail::to($demande->user->email)
                ->cc($controleur->email?? null)
                ->send(new DemandeRefuseeDaf($demande));
        }

        return redirect()->route('demandes.enAttenteDaf')->with('error', 'Demande refusée par le DAF.');
    }

    return redirect()->route('demandes.enAttenteDaf')->with('error', 'Impossible de refuser cette demande.');
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

    return view('demandes.show_enattente_directeur', compact('demande'));
    

}
// Valider la demande par le Directeur
public function validerDirecteur($id)
{
    $demande = Demande::with('entite', 'user')->find($id); // Charger l'entité et l'initiateur

    if ($demande && $demande->status == 2) {
        $demande->status = 3; // Statut validé par DG
        $demande->date_validation_dg = now();
        $demande->save();

        // Récupérer la Trésorie de l'entité
        $tresories = User::whereHas('role', function ($q) use ($demande) {
            $q->whereRaw('LOWER(libelle) = ?', ['tresorie'])
              ->where('entite_id', $demande->entite_id);
        })->get();

        // Récupérer le DAF qui a validé
        $daf = User::whereHas('role', function($q) use ($demande) {
            $q->whereRaw('LOWER(libelle) = ?', ['daf'])
              ->where('entite_id', $demande->entite_id);
        })->first();

        // Récupérer le contrôleur de l'entité
        $controleur = User::whereHas('role', function($q) use ($demande) {
            $q->whereRaw('LOWER(libelle) = ?', ['controleur'])
              ->where('entite_id', $demande->entite_id);
        })->first();

        // Envoi du mail à la Trésorie avec copies à l'initiateur, au DAF et au contrôleur
        foreach ($tresories as $tresorie) {
           Mail::to($tresorie->email)
                ->cc(array_filter([
                    $demande->user->email ?? null,
                    $daf->email ?? null,
                    $controleur->email ?? null,
                ]))
                ->send(new NotificationTresorie($demande));
        }

        return redirect()->route('demandes.enAttenteDirecteur')
                         ->with('success', 'Demande validée et envoyée à la Trésorie.');
    }

    return redirect()->route('demandes.enAttenteDirecteur')
                     ->with('error', 'Impossible de valider cette demande.');
}


// Refuser la demande par le Directeur



public function RefuserDirecteur($id)
{
    $demande = Demande::with('entite', 'user')->findOrFail($id);

    if ($demande->status == 2) {
        $demande->status = -3; // Refusée par le DG
        $demande->date_validation_dg = now();
        $demande->save();

        // Envoi du mail à l'initiateur avec DAF et contrôleur en copie
        $daf = User::whereHas('role', fn($q) => $q->where('entite_id', $demande->entite_id)
                                                   ->whereRaw('LOWER(libelle) = ?', ['daf']))
                                                   ->first();
        $controleur = User::whereHas('role', fn($q) => $q->where('entite_id', $demande->entite_id)
                                                          ->whereRaw('LOWER(libelle) = ?', ['controleur']))
                                                           ->first();

        Mail::to($demande->user->email)
            ->cc(array_filter([$daf->email ?? null, $controleur->email ?? null]))
            ->send(new DemandeRefuseeDG($demande));

        return redirect()->route('demandes.enAttenteDirecteur')->with('success', 'Demande refusée avec succès.');
    }

    return redirect()->route('demandes.enAttenteDirecteur')->with('error', 'Impossible de refuser cette demande.');
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
    $demande = Demande::findOrFail($id);
    return view('demandes.show_valider', compact('demande'));
}

//impression demandes de paiements
public function imprimer($id)
{
    $demande = Demande::with('user', 'entite')->findOrFail($id);

    // Affiche la vue HTML pour impression
    return view('demandes.dp_imprimer', compact('demande'));
}



}
