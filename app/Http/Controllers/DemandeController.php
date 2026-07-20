<?php

namespace App\Http\Controllers;

use App\Mail\DemandeRefusee;
use App\Mail\DemandeRefuseeDaf;
use App\Mail\DemandeRefuseeDG;
use App\Mail\NotificationDAF;
use App\Mail\NotificationDG;
use App\Mail\NouvelleDemandeDP;
use App\Models\Demande;
use App\Models\Entite; // ou DemandePaiementMail selon le nom que tu as donné
use App\Models\HistoriqueAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DemandeController extends Controller
{
    private function verifierPermission($user, $demande, $niveau)
    {
        //  Vérifications de sécurité
        if (! $user || ! $user->role || ! $demande || ! $demande->entite) {
            return false;
        }
        //  Slug de l'entité
        $slugEntite = Str::slug($demande->entite->libelle_entite, '_');
        // Permission demandée (niveau courant)
        $permissionName = "valider_demande_niveau{$niveau}_{$slugEntite}";
        // Liste des permissions du rôle
        $permissions = $user->role->permissions->pluck('nom');
        //  Cas 1 : permission exacte
        if ($permissions->contains($permissionName)) {
            return true;
        }
        //  Cas 2 : permission globale niveau123
        // uniquement si la demande est totalement validée (status = 3)
        $permissionGlobale = "valider_demande_niveau123_{$slugEntite}";
        if (
            $permissions->contains($permissionGlobale)
            && $demande->status == 3
        ) {
            return true;
        }

        return false;
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

    // Vérifie s'il existe au moins un validateur pour le niveau suivant
    private function verifierExistenceValidateurNiveauSuivant($demande, $niveauSuivant)
    {
        $slugEntite = Str::slug($demande->entite->libelle_entite, '_');

        $permission = "valider_demande_niveau{$niveauSuivant}_{$slugEntite}";

        return User::whereHas('role.permissions', function ($query) use ($permission) {
            $query->where('nom', $permission);
        })->exists();
    }

    /**
     * Liste toutes les demandes
     */
    public function index()
    {
        $user = Auth::user();

        // Permissions utilisateur
        $permissions = $user->role->permissions->pluck('nom');

        // Extraire les entités autorisées pour l'historique
        $entitesAutorisees = $permissions
            ->filter(fn ($p) => str_starts_with($p, 'voir_historique_demande_'))
            ->map(fn ($p) => str_replace('voir_historique_demande_', '', $p));

        // Filtrer les demandes selon les entités autorisées
        $demandes = Demande::with(['entite', 'user'])
            ->whereHas('entite', function ($query) use ($entitesAutorisees) {
                $query->whereIn(
                    \DB::raw('LOWER(REPLACE(libelle_entite, " ", "_"))'),
                    $entitesAutorisees
                );
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('demandes.index', compact('demandes', 'permissions'));
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
                'montant_ht' => 'required|numeric|min:1',
                'tva' => 'required|string|in:'.implode(',', array_keys($tvaOptions)),
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
            $permissionName = 'valider_demande_niveau1_'.$slugEntite;

            //  Récupération des validateurs
            $validateurs = User::with(['role.permissions'])
                ->whereHas('role.permissions', function ($query) use ($permissionName) {
                    $query->where('nom', $permissionName);
                })
                ->get();

            if ($validateurs->isEmpty()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Impossible de créer la demande : aucun utilisateur n'a la permission  pour valider sur  l'entité {$entite->libelle_entite}.");
            }

            // Calcul du montant TTC
            $montantHT = $validated['montant_ht'];
            $tvaRate = $tvaOptions[$validated['tva']];
            $validated['montant_paiement_fournisseur'] = $montantHT + ($montantHT * $tvaRate / 100);

            //  Génération de la référence DP
            $validated['reference_dp'] = 'DP-CI'.date('dmY').'-'.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $validated['user_id'] = auth()->id();
            $validated['status'] = 0;

            //  Gestion des fichiers joints par dossier spécifique
            if ($request->hasFile('pieces_jointes')) {
                $folder = 'pieces_jointes/'.$validated['reference_dp'];
                $storedFiles = [];

                foreach ($request->file('pieces_jointes') as $file) {
                    $filename = $file->getClientOriginalName();
                    $path = $file->storeAs($folder, $filename, 'public'); // stockage public
                    $storedFiles[] = $path;
                }

                $validated['pieces_jointes'] = json_encode($storedFiles); // on stocke un JSON des chemins
            }

            //  Création de la demande
            $demande = Demande::create($validated);
            HistoriqueAction::create([
                'user_id' => auth()->id(),
                'action' => 'soumettre_demande',
                'subject_type' => Demande::class,
                'subject_id' => $demande->id,
                'entite_id' => $validated['entite_id'],
                'properties' => [
                    'reference_dp' => $demande->reference_dp,
                    'montant_ht' => $demande->montant_ht,
                    'montant_ttc' => $demande->montant_paiement_fournisseur,
                ],
                'created_at' => now(),
            ]);
            //  Envoi du mail aux validateurs
            foreach ($validateurs as $user) {
                Mail::to($user->email)->send(
                    new NouvelleDemandeDP($demande, $user)
                );
            }

            return redirect()->route('demandes.create')
                ->with('success', "Demande {$demande->reference_dp} - {$demande->denomination} créée avec succès et notifiée aux validateurs !");

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la demande DP : '.$e->getMessage());

            return redirect()->route('demandes.create')
                ->with('error', 'Une erreur est survenue lors de la création de la demande. Veuillez réessayer.');
        }
    }

    /**
     * Affiche une demande
     */
    public function show(Demande $demande)
    {
        $demande->load(['entite', 'user']);

        $pieces = [];

        if ($demande->pieces_jointes) {
            $pieces = json_decode($demande->pieces_jointes, true);
        }

        return view('demandes.show', compact('demande', 'pieces'));
    }

    /**
     * en attente controlleur
     */
    public function enattenteControl()
    {
        $user = Auth::user();

        $permissions = $user->role->permissions->pluck('nom');

        $entitesAutorisees = $permissions
            ->filter(fn ($p) => str_starts_with($p, 'voir_demande_valider1_'))
            ->map(fn ($p) => str_replace('voir_demande_valider1_', '', $p));

        $demandes = Demande::with(['entite', 'user'])
            ->where('status', 0)
            ->whereHas('entite', function ($query) use ($entitesAutorisees) {
                $query->whereIn(\DB::raw('LOWER(REPLACE(libelle_entite, " ", "_"))'), $entitesAutorisees);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('demandes.controleur', compact('demandes', 'permissions'));
    }

    public function showEnAttenteControl($id)
    {
        $demande = Demande::with(['entite', 'user'])
            ->where('id', $id)
            ->where('status', 0)
            ->firstOrFail();

        $user = Auth::user();

        // Construire le nom de permission selon l'entité

        // Vérifier permission niveau 1
        if (! $this->verifierPermission($user, $demande, 1)) {
            abort(403, "Vous n'avez pas l'autorisation de voir cette demande.");
        }

        // Décoder les pièces jointes
        $pieces = $demande->pieces_jointes
            ? json_decode($demande->pieces_jointes, true)
            : [];
        $peutValiderNiveau2 = $this->verifierExistenceValidateurNiveauSuivant($demande, 2);

        return view('demandes.show_enattente', compact('demande', 'pieces', 'peutValiderNiveau2'));
    }

    public function validerControleur($id)
    {
        $demande = Demande::with('entite', 'user')->findOrFail($id);

        $user = Auth::user();

        //  Vérifier si la demande est encore en attente
        if ($demande->status != 0) {
            return redirect()->back()->with('error', 'Cette demande ne peut plus être validée.');
        }

        //  Vérification permission du niveau 1 (contrôleur)
        if (! $this->verifierPermission($user, $demande, 1)) {
            abort(403, "Vous n'avez pas la permission de valider cette demande.");
        }

        // Vérifier l'existence d'un validateur au niveau suivant (niveau 2)
        if (! $this->verifierExistenceValidateurNiveauSuivant($demande, 2)) {
            return redirect()->back()->with('error', "Aucun utilisateur n'a la permission pour valider au niveau suivant.");
        }

        // Validation
        $demande->status = 1;
        $demande->date_validation_controleur = now();
        $demande->save();
        //  ENREGISTREMENT HISTORIQUE
        HistoriqueAction::create([
            'user_id' => $user->id,
            'action' => 'valider_niveau1',
            'subject_type' => Demande::class,
            'subject_id' => $demande->id,
            'entite_id' => $demande->entite_id,
            'properties' => [
                'reference_dp' => $demande->reference_dp,
                'status' => $demande->status,
            ],
            'created_at' => now(),
        ]);

        //  Notification vers le niveau suivant (DAF)
        $this->envoyerNotificationNiveau($demande, 2);

        return redirect()->route('demandes.enAttenteControl')
            ->with('success', 'Demande envoyée au niveau suivant.');
    }

    public function refuserControleur(Request $request, $id)
    {
        $demande = Demande::with('user', 'entite')->findOrFail($id);
        $user = Auth::user();

        //  Validation du motif
        $request->validate([
            'motif_refus' => 'required|string|max:1000',
        ]);

        //  Vérifier statut
        if ($demande->status != 0) {
            return redirect()->back()->with('error', 'Cette demande ne peut plus être refusée.');
        }

        // Vérifier permission
        if (! $this->verifierPermission($user, $demande, 1)) {
            abort(403, "Vous n'avez pas la permission de refuser cette demande.");
        }

        //  Mise à jour
        $demande->status = -1;
        $demande->motif_refus = $request->motif_refus; //  important
        $demande->refuse_par = $user->prenom.' '.$user->nom;
        $demande->date_validation_controleur = now();
        $demande->save();
        // HISTORIQUE
        HistoriqueAction::create([
            'user_id' => $user->id,
            'action' => 'refus_niveau1',
            'subject_type' => Demande::class,
            'subject_id' => $demande->id,
            'entite_id' => $demande->entite_id,
            'properties' => [
                'reference_dp' => $demande->reference_dp,
                'motif_refus' => $request->motif_refus,
                'status' => $demande->status,
            ],
            'created_at' => now(),
        ]);
        //  Email
        if ($demande->user && $demande->user->email) {
            Mail::to($demande->user->email)
                ->send(new DemandeRefusee($demande));
        }

        return redirect()->route('demandes.enAttenteControl')
            ->with('error', 'La demande a été refusée avec succès.');
    }
    // DemandeController.php

    public function enAttenteDaf()
    {
        $user = Auth::user();

        // Récupérer toutes les permissions
        $permissions = $user->role->permissions->pluck('nom');

        // Extraire les entités autorisées pour niveau 2 (DAF)
        $entitesAutorisees = $permissions
            ->filter(fn ($p) => str_starts_with($p, 'voir_demande_valider2_'))
            ->map(fn ($p) => str_replace('voir_demande_valider2_', '', $p));

        // Récupérer les demandes filtrées
        $demandes = Demande::with(['entite', 'user'])
            ->where('status', 1) // en attente DAF
            ->whereHas('entite', function ($query) use ($entitesAutorisees) {
                $query->whereIn(
                    \DB::raw('LOWER(REPLACE(libelle_entite, " ", "_"))'),
                    $entitesAutorisees
                );
            })
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('demandes.daf', compact('demandes', 'permissions'));
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
        if (! $this->verifierPermission($user, $demande, 2)) {
            abort(403, "Vous n'avez pas l'autorisation de voir cette demande.");
        }

        // Décoder les pièces jointes
        $pieces = $demande->pieces_jointes
            ? json_decode($demande->pieces_jointes, true)
            : [];
        $peutValiderNiveau3 = $this->verifierExistenceValidateurNiveauSuivant($demande, 3);

        return view('demandes.show_enAttenteDaf', compact('demande', 'pieces', 'peutValiderNiveau3'));
    }

    // validation du DAF
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
        if (! $this->verifierPermission($user, $demande, 2)) {
            abort(403, "Vous n'avez pas la permission.");
        }

        // Mise à jour
        $demande->status = 2;
        $demande->date_validation_daf = now();
        $demande->save();
        //  HISTORIQUE
        HistoriqueAction::create([
            'user_id' => $user->id,
            'action' => 'valider_niveau2',
            'subject_type' => Demande::class,
            'subject_id' => $demande->id,
            'entite_id' => $demande->entite_id,
            'properties' => [
                'reference_dp' => $demande->reference_dp,
                'status' => $demande->status,
            ],
            'created_at' => now(),
        ]);
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

    public function refuserDaf(Request $request, $id)
    {
        $demande = Demande::with('user', 'entite')->findOrFail($id);
        $user = Auth::user();
        //  Validation du motif
        $request->validate([
            'motif_refus' => 'required|string|max:1000',
        ]);
        // Vérifier si le statut est valide pour refus (niveau DAF)
        if ($demande->status != 1) {
            return redirect()->back()->with('error', 'Cette demande ne peut plus être refusée.');
        }

        // Vérifier permission niveau 2
        if (! $this->verifierPermission($user, $demande, 2)) {
            abort(403, "Vous n'avez pas la permission de refuser cette demande.");
        }

        // Mettre à jour le statut
        $demande->status = -2;
        $demande->refuse_par = $user->prenom.' '.$user->nom;
        $demande->motif_refus = $request->motif_refus; //  important
        $demande->date_validation_daf = now();
        $demande->save();
        //  HISTORIQUE
        HistoriqueAction::create([
            'user_id' => $user->id,
            'action' => 'refuser_niveau2',
            'subject_type' => Demande::class,
            'subject_id' => $demande->id,
            'entite_id' => $demande->entite_id,
            'properties' => [
                'reference_dp' => $demande->reference_dp,
                'motif_refus' => $request->motif_refus,
                'status' => $demande->status,
            ],
            'created_at' => now(),
        ]);
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
        $user = Auth::user();

        // Récupérer les permissions
        $permissions = $user->role->permissions->pluck('nom');

        // Extraire les entités autorisées pour niveau 3 (DG)
        $entitesAutorisees = $permissions
            ->filter(fn ($p) => str_starts_with($p, 'voir_demande_valider3_'))
            ->map(fn ($p) => str_replace('voir_demande_valider3_', '', $p));

        // Récupérer les demandes filtrées
        $demandes = Demande::with(['entite', 'user'])
            ->where('status', 2) // en attente DG
            ->whereHas('entite', function ($query) use ($entitesAutorisees) {
                $query->whereIn(
                    \DB::raw('LOWER(REPLACE(libelle_entite, " ", "_"))'),
                    $entitesAutorisees
                );
            })
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('demandes.directeur', compact('demandes', 'permissions'));
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
        if (! $this->verifierPermission($user, $demande, 3)) {
            abort(403, "Vous n'avez pas l'autorisation de voir cette demande.");
        }

        // Décoder les pièces jointes
        $pieces = $demande->pieces_jointes
            ? json_decode($demande->pieces_jointes, true)
            : [];

        return view('demandes.show_enattente_directeur', compact('demande', 'pieces'));
    }

    // Valider la demande par le Directeur
    public function validerDirecteur($id)
    {
        $demande = Demande::with('entite', 'user')->findOrFail($id);
        $user = Auth::user();
        if ($demande->status != 2) {
            return redirect()->route('demandes.enAttenteDirecteur')
                ->with('error', 'Impossible de valider cette demande.');
        }

        // Mise à jour du statut et date validation DG
        $demande->status = 3;
        $demande->date_validation_dg = now();
        $demande->save();
        HistoriqueAction::create([
            'user_id' => $user->id,
            'action' => 'valider_niveau3',
            'subject_type' => Demande::class,
            'subject_id' => $demande->id,
            'entite_id' => $demande->entite_id,
            'properties' => [
                'reference_dp' => $demande->reference_dp,
                'status' => $demande->status,
            ],
            'created_at' => now(),
        ]);
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
        if ($daf) {
            $cc[] = $daf->email;
        }

        $controleur = User::whereHas('role.permissions', function ($q) use ($slug) {
            $q->where('nom', "valider_demande_niveau1_{$slug}");
        })->first();
        if ($controleur) {
            $cc[] = $controleur->email;
        }

        // Notification niveau 3
        $this->envoyerNotificationNiveau($demande, 3, $cc);

        return redirect()->route('demandes.enAttenteDirecteur')
            ->with('success', 'Demande validée, paiement créé et notifications envoyées.');
    }
    // Refuser la demande par le Directeur

    public function refuserDirecteur(Request $request, $id)
    {
        $demande = Demande::with('user', 'entite')->findOrFail($id);
        $user = Auth::user();
        $request->validate([
            'motif_refus' => 'required|string|max:1000',
        ]);
        // Vérifier si le statut est valide pour refus (niveau DG)
        if ($demande->status != 2) {
            return redirect()->back()->with('error', 'Cette demande ne peut plus être refusée.');
        }

        // Vérifier permission niveau 3
        if (! $this->verifierPermission($user, $demande, 3)) {
            abort(403, "Vous n'avez pas la permission de refuser cette demande.");
        }

        // Mettre à jour le statut
        $demande->status = -3;
        $demande->refuse_par = $user->prenom.' '.$user->nom;
        $demande->motif_refus = $request->motif_refus; //  important
        $demande->date_validation_dg = now();
        $demande->save();
        // HISTORIQUE
        HistoriqueAction::create([
            'user_id' => $user->id,
            'action' => 'refuser_niveau3',
            'subject_type' => Demande::class,
            'subject_id' => $demande->id,
            'entite_id' => $demande->entite_id,
            'properties' => [
                'reference_dp' => $demande->reference_dp,
                'motif_refus' => $request->motif_refus,
                'status' => $demande->status,
            ],
            'created_at' => now(),
        ]);
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
        $user = Auth::user();

        // Permissions utilisateur
        $permissions = $user->role->permissions->pluck('nom');

        // Extraire les entités autorisées pour les demandes validées
        $entitesAutorisees = $permissions
            ->filter(fn ($p) => str_starts_with($p, 'voir_demande_valider123_'))
            ->map(fn ($p) => str_replace('voir_demande_valider123_', '', $p));

        // Filtrer les demandes validées selon les entités autorisées
        $demandes = Demande::with(['entite', 'user'])
            ->where('status', 3)
            ->whereHas('entite', function ($query) use ($entitesAutorisees) {
                $query->whereIn(
                    \DB::raw('LOWER(REPLACE(libelle_entite, " ", "_"))'),
                    $entitesAutorisees
                );
            })
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('demandes.valider', compact('demandes', 'permissions'));
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
        if (! $this->verifierPermission($user, $demande, 3)) {
            abort(403, "Vous n'avez pas l'autorisation de voir cette demande.");
        }

        // 3️ Décoder les pièces jointes
        $pieces = $demande->pieces_jointes
            ? json_decode($demande->pieces_jointes, true)
            : [];

        // 4️ Retourner la vue
        return view('demandes.show_valider', compact('demande', 'pieces'));
    }

    // impression demandes de paiements
    public function imprimer($id)
    {
        $user = Auth::user();
        $demande = Demande::with('user', 'entite')->findOrFail($id);
        HistoriqueAction::create([
            'user_id' => $user->id,
            'action' => 'imprimer',
            'subject_type' => Demande::class,
            'subject_id' => $demande->id,
            'entite_id' => $demande->entite_id,
            'properties' => [
                'reference_dp' => $demande->reference_dp,
            ],
        ]);

        // Affiche la vue HTML pour impression
        return view('demandes.dp_imprimer', compact('demande'));
    }
}
