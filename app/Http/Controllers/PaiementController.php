<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Models\Demande;
use App\Models\PaiementVersement;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PaiementAValiderMail;
use App\Mail\PaiementValideMail;
use App\Mail\PaiementRefuseMail;
use Illuminate\Support\Facades\DB;
use App\Models\HistoriqueAction;
class PaiementController extends Controller
{
   
/** Recalcul des montants et du statut global */
private function recalculerMontantsEtStatut(Paiement $paiement)
{
    // Total déjà payé via les versements
    $paiement->montant_paye = $paiement->montantDejaPaye();

    //  On ne stocke plus montant_restant en base
    // On le calcule dynamiquement si besoin
    $montantRestant = max(0, $paiement->montant_prevu - $paiement->montant_paye);

    // Statut
    if ($montantRestant == 0) {
        $paiement->statut = 'termine';
    } elseif ($paiement->montant_paye > 0) {
        $paiement->statut = 'partiel';
    } else {
        $paiement->statut = 'en_attente';
    }

    $paiement->save();
}

public function index()
    {
        $dernieresDemandes = Demande::with(['paiement.paiementVersements', 'user'])
            ->where('status', 3)
            ->orderByDesc('date_validation_dg')
            ->paginate(10);

        foreach ($dernieresDemandes as $demande) {
            if ($demande->paiement) {
                $this->recalculerMontantsEtStatut($demande->paiement);
            }
        }

        return view('paiements.faire_paiement', [
            'dernieresDemandes' => $dernieresDemandes,
            'paiement' => null,
            'demande' => null,
            'montantTotal' => 0,
            'montantDejaPaye' => 0,
            'montantRestant' => 0,
            'versementEnAttente' => false,
            'error' => null
        ]);
    }   

 
 /** ─────────────────────────────────────────────
     * Recherche d'une demande par référence
     * ───────────────────────────────────────────── */
 public function search(Request $request)
{
    //  Récupérer la référence saisie
    $reference = $request->input('reference_dp');

    //  Récupérer la demande VALIDÉE avec ses relations utiles
    $demande = Demande::with(['entite', 'user'])
        ->where('reference_dp', $reference)
        ->where('status', 3)
        ->first();

    //  Si aucune demande trouvée
    if (!$demande) {
        return redirect()->back()
            ->with('error', "Aucune demande trouvée ou non validée.");
    }

    //  Récupérer le paiement + ses versements
    // (on charge aussi la demande pour éviter les requêtes inutiles dans la vue)
    $paiement = Paiement::with(['paiementVersements', 'demande'])
        ->where('demande_id', $demande->id)
        ->first();

    //  Sécurité : paiement inexistant
    if (!$paiement) {
        return redirect()->back()
            ->with('error', "Paiement introuvable pour cette demande.");
    }

    //  Recalcul automatique des montants et du statut
    $this->recalculerMontantsEtStatut($paiement);

    //  Vérifier s'il existe un versement en attente
    $versementEnAttente = $paiement->paiementVersements()
        ->where('statut_versement', 'en_attente')
        ->exists();

    //  Sécuriser les pièces jointes (toujours un tableau)
    $piecesJointes = is_array($demande->pieces_jointes)
        ? $demande->pieces_jointes
        : [];

    // Retour vers la vue
    return view('paiements.show_paiement', [
        'demande' => $demande,
        'paiement' => $paiement,
        'piecesJointes' => $piecesJointes, //  IMPORTANT
        'montantTotal' => $paiement->montant_prevu,
        'montantDejaPaye' => $paiement->montant_paye,
        'montantRestant' => $paiement->montant_restant,
        'versementEnAttente' => $versementEnAttente,
        'error' => null,
    ]);
}



  /** ─────────────────────────────────────────────
     * Enregistrer un nouveau versement
     * ───────────────────────────────────────────── */
public function payer(Request $request, $paiementId)
{
    $paiement = Paiement::with('paiementVersements', 'demande.entite')
        ->findOrFail($paiementId);

    //  Déjà payé
    if ($paiement->statut === 'termine') {
        return redirect()->route('paiements.index')
            ->with('error', 'Paiement déjà complètement payé.');
    }

    // BLOQUAGE : versement en attente
    $versementEnAttente = $paiement->paiementVersements()
        ->where('statut_versement', 'en_attente')
        ->exists();

    if ($versementEnAttente) {
        return redirect()->route('paiements.index')
            ->with('error', "Un versement est déjà en attente de validation.");
    }

    //  Vérification responsable
    $entiteSlug = Str::slug($paiement->demande->entite->libelle_entite, '_');

    $responsable = User::whereHas('role.permissions', function ($q) use ($entiteSlug) {
        $q->where('nom', "valider_paiement_niveau1_$entiteSlug");
    })->first();

    if (!$responsable) {
        return redirect()->route('paiements.index')
            ->with('error', "Aucun responsable a la permission de valider ce paiement.");
    }

    //  Données
    $montant = floatval($request->input('montant'));
    $commentaire = $request->input('commentaire');

    if ($montant <= 0) {
        return redirect()->route('paiements.index')
            ->with('error', 'Montant invalide.');
    }

    $montantRestant = $paiement->montantRestant();

    if ($montant > $montantRestant) {
        return redirect()->route('paiements.index')
            ->with('error', 'Montant supérieur au reste.');
    }

    //  Création versement
    $versement = PaiementVersement::create([
        'id' => Str::uuid(),
        'paiement_id' => $paiement->id,
        'montant' => $montant,
        'commentaire' => $commentaire,
        'date_versement' => now(),
        'statut_versement' => 'en_attente',
    ]);
    HistoriqueAction::create([
    'user_id'      => auth()->id(),
    'action'       => 'soumettre_versement',
    'subject_type' => Paiement::class,
    'subject_id'   => $paiement->id,
    'entite_id'    => $paiement->demande->entite_id,

    'properties'   => [
        'reference_dp' => $paiement->demande->reference_dp,
        'montant'      => $montant,
        'commentaire'  => $commentaire,
        'versement_id' => $versement->id,
        'statut'       => 'en_attente',
    ],

    'created_at'   => now(),
]);

    //  Recalcul
    $this->recalculerMontantsEtStatut($paiement);

    //  Mail
    Mail::to($responsable->email)
        ->send(new PaiementAValiderMail($paiement, $versement, $responsable));

    // RETOUR VERS INDEX (SANS BOUCLE)
    return redirect()->route('paiements.index')
        ->with('success', "Versement de " . number_format($montant, 0, ',', ' ') . " F CFA enregistré.");
}


/** ─────────────────────────────────────────────
     * Validation DG d’un versement
     * ───────────────────────────────────────────── */
public function validerDG($paiementId)
{
    $paiement = Paiement::with('paiementVersements', 'demande.user', 'demande.entite')
        ->findOrFail($paiementId);

    $user = auth()->user();
    $slug = Str::slug($paiement->demande->entite->libelle_entite, '_');
    $permissionRequise = "valider_paiement_niveau1_{$slug}";

    //  Vérification de permission (COMME demandeController)
    $hasPermission = $user->role
        ? $user->role->permissions->pluck('nom')->contains($permissionRequise)
        : false;

    if (!$hasPermission) {
        return back()->with('error', "Vous n'avez pas la permission de valider ce paiement.");
    }

    //  Récupérer le dernier versement en attente
    $dernierVersement = $paiement->paiementVersements()
        ->where('statut_versement', 'en_attente')
        ->latest('created_at')
        ->first();

    if (!$dernierVersement) {
        return back()->with('error', 'Aucun versement en attente à valider.');
    }

    //  Validation
    $dernierVersement->update([
        'statut_versement' => 'valide'
    ]);

    //  Recalcul
    $this->recalculerMontantsEtStatut($paiement);

    //  Mail
    Mail::to($paiement->demande->user->email)
        ->send(new PaiementValideMail($paiement, $dernierVersement));

    return back()->with('success', "Versement validé avec succès.");
}

   /** ─────────────────────────────────────────────
     * Refus DG d’un versement
     * ───────────────────────────────────────────── */
 public function refuserDG(Request $request, $paiementId)
{
    $paiement = Paiement::with('paiementVersements', 'demande.user', 'demande.entite')
        ->findOrFail($paiementId);

    $user = auth()->user();

    // validation motif
    $request->validate([
        'motif_refus_versement' => 'required|string|max:1000'
    ]);

    $slug = Str::slug($paiement->demande->entite->libelle_entite, '_');
    $permissionRequise = "valider_paiement_niveau1_{$slug}";

    $hasPermission = $user->role
        ? $user->role->permissions->pluck('nom')->contains($permissionRequise)
        : false;

    if (!$hasPermission) {
        return redirect()->route('paiements.encours')
            ->with('error', "Vous n'avez pas la permission de refuser ce paiement.");
    }

    // dernier versement en attente
    $dernierVersement = $paiement->paiementVersements()
        ->where('statut_versement', 'en_attente')
        ->latest()
        ->first();

    if (!$dernierVersement) {
       return redirect()->route('paiements.encours')
    ->with('error', 'Aucun versement en attente à refuser.');
    }

    // refus
    $dernierVersement->update([
        'statut_versement' => 'refuse',
        'commentaire' => $request->motif_refus_versement
    ]);

    // recalcul
    $this->recalculerMontantsEtStatut($paiement);

    // HISTORIQUE
    HistoriqueAction::create([
        'user_id'      => $user->id,
        'action'       => 'refuser_versement',
        'subject_type' => Paiement::class,
        'subject_id'   => $paiement->id,
        'entite_id'    => $paiement->demande->entite_id,
        'properties'   => [
            'reference_dp' => $paiement->demande->reference_dp,
            'motif'        => $request->motif_refus_versement,
            'versement_id' => $dernierVersement->id,
        ],
        'created_at'   => now(),
    ]);

    // notification
    Mail::to($paiement->demande->user->email)
        ->send(new PaiementRefuseMail($paiement, $dernierVersement));

   return redirect()->route('paiements.encours')
    ->with('success', 'Versement refusé avec succès. L’initiateur a été notifié.');
}
    /** Notification DG */
   
   /** ─────────────────────────────────────────────
     * Notification DG pour versement à valider
     * ───────────────────────────────────────────── */
    protected function notifierDG(Paiement $paiement)
    {
        $dernierVersement = $paiement->paiementVersements()
            ->where('statut_versement', 'en_attente')
            ->latest('created_at')
            ->first();

        if (!$dernierVersement) return;

        $dgRole = Role::where('libelle', 'DG')
            ->where('entite_id', $paiement->demande->entite_id)
            ->first();

        if (!$dgRole) return;

        $dgs = User::where('role_id', $dgRole->id)->get();

        foreach ($dgs as $dg) {
            Mail::to($dg->email)->send(new PaiementAValiderMail($paiement, $dernierVersement));
        }
    }

    /** ─────────────────────────────────────────────
     * Liste des paiements en cours (attente DG)
     * ───────────────────────────────────────────── */
public function encours()
{
    $user = auth()->user();

    //  Récupérer les permissions
    $permissions = $user->role
        ? $user->role->permissions->pluck('nom')
        : collect();

    //  Extraire les entités autorisées
    $entitesAutorisees = $permissions
        ->filter(fn($p) => str_starts_with($p, 'valider_paiement_niveau1_'))
        ->map(fn($p) => str_replace('valider_paiement_niveau1_', '', $p));

    //  Requête principale (comme ta méthode)
    $paiements = Paiement::with([
            'demande.entite',
            'demande.user',
            'paiementVersements' => function ($q) {
                $q->latest();
            }
        ])
        //  Paiements ayant au moins un versement en attente
        ->whereHas('paiementVersements', function ($q) {
            $q->where('statut_versement', 'en_attente');
        })

        //  Filtre sur les demandes VALIDÉES
        ->whereHas('demande', function ($q) use ($entitesAutorisees) {
            $q->where('status', 3)
              ->whereHas('entite', function ($query) use ($entitesAutorisees) {
                  $query->whereIn(
                      \DB::raw('LOWER(REPLACE(libelle_entite, " ", "_"))'),
                      $entitesAutorisees
                  );
              });
        })

        ->orderBy('created_at', 'desc')
        ->paginate(12);

    //  Calculs comme avant
   

    return view('paiements.encours', compact('paiements', 'permissions'));
}
    /** ─────────────────────────────────────────────
     * Liste des paiements partiellement payés
     * ───────────────────────────────────────────── */
public function partiellement()
{
    $user = auth()->user();

    // Permissions utilisateur
    $permissions = $user->role
        ? $user->role->permissions->pluck('nom')
        : collect();

    // Extraire les entités autorisées pour l'historique paiement
    $entitesAutorisees = $permissions
        ->filter(fn($p) => str_starts_with($p, 'voir_historique_paiement_'))
        ->map(fn($p) => str_replace('voir_historique_paiement_', '', $p));

    // Si aucune entité autorisée → aucun résultat
    if ($entitesAutorisees->isEmpty()) {
        $paiements = collect();

        return view('paiements.partiellement', compact('paiements', 'permissions'));
    }

    // Paiements filtrés + statut partiel/en attente
    $paiements = Paiement::with(['demande.entite', 'paiementVersements'])
        ->whereIn('statut', ['en_attente', 'partiel'])
        ->whereHas('demande.entite', function ($query) use ($entitesAutorisees) {
            $query->whereIn(
                \DB::raw('LOWER(REPLACE(libelle_entite, " ", "_"))'),
                $entitesAutorisees
            );
        })
        ->orderByDesc('created_at')
        ->paginate(12);

    return view('paiements.partiellement', compact('paiements', 'permissions'));
} /** ─────────────────────────────────────────────
     * Liste des paiements soldés
     * ───────────────────────────────────────────── */
public function valides()
{
    $user = auth()->user();

    // Permissions utilisateur
    $permissions = $user->role
        ? $user->role->permissions->pluck('nom')
        : collect();

    // Extraire les entités autorisées pour l'historique paiement
    $entitesAutorisees = $permissions
        ->filter(fn($p) => str_starts_with($p, 'voir_historique_paiement_'))
        ->map(fn($p) => str_replace('voir_historique_paiement_', '', $p));

    // Si aucune entité autorisée → vide propre
    if ($entitesAutorisees->isEmpty()) {
        $paiements = collect();

        return view('paiements.valides', compact('paiements', 'permissions'));
    }

    // Paiements terminés uniquement
    $paiements = Paiement::with([
            'demande.entite',
            'paiementVersements' => function ($q) {
                $q->latest();
            }
        ])
        ->where('statut', 'termine')
        ->whereHas('demande.entite', function ($query) use ($entitesAutorisees) {
            $query->whereIn(
                \DB::raw('LOWER(REPLACE(libelle_entite, " ", "_"))'),
                $entitesAutorisees
            );
        })
        ->latest()
        ->paginate(12);

    return view('paiements.valides', compact('paiements', 'permissions'));
}


// Liste de tous les paiements émis (tous statuts confondus)

public function emis()
{
    $user = auth()->user();

    // Permissions utilisateur
    $permissions = $user->role
        ? $user->role->permissions->pluck('nom')
        : collect();

    // Extraire les entités autorisées pour les paiements
    $entitesAutorisees = $permissions
        ->filter(fn($p) => str_starts_with($p, 'voir_historique_paiement_'))
        ->map(fn($p) => str_replace('voir_historique_paiement_', '', $p));

    // Filtrer les paiements selon les entités autorisées
    $paiements = Paiement::with(['demande.entite', 'paiementVersements'])
        ->whereHas('demande.entite', function ($query) use ($entitesAutorisees) {
            $query->whereIn(
                \DB::raw('LOWER(REPLACE(libelle_entite, " ", "_"))'),
                $entitesAutorisees
            );
        })
        ->orderByDesc('created_at')
        ->paginate(12);

    return view('paiements.emis', compact('paiements', 'permissions'));
}
  // Affichage d’un paiement (vue normale) 
public function show($id)
{
    // Récupérer le paiement par ID avec la demande et les versements
    $paiement = Paiement::with(['demande', 'paiementVersements'])->find($id);

    if (!$paiement) {
        return redirect()->back()->with('error', 'Ce paiement n\'existe pas.');
    }

    // Calculer les montants déjà payés et restants
    $paiement->montant_paye = $paiement->montantDejaPaye();
    $paiement->montant_restant = $paiement->montantRestant();

    // Totaux pour l'affichage du header (facultatif)
    $totalEmis     = Paiement::count();
    $totalEncours  = Paiement::where('statut', 'en_attente')->count();
    $totalPartiels = Paiement::where('statut', 'partiel')->count();
    $totalValide   = Paiement::where('statut', 'termine')->count();

    return view('paiements.show', compact(
        'paiement',
        'totalEmis',
        'totalEncours',
        'totalPartiels',
        'totalValide'
    ));
}
// Route spécifique pour la vue DG (avant la route dynamique)
public function shown($paiementId)
{
    $paiement = Paiement::with([
        'paiementVersements',
        'demande.user',
        'demande.entite'
    ])->findOrFail($paiementId);

    $user = auth()->user();
    $slug = Str::slug($paiement->demande->entite->libelle_entite, '_');
    $permissionDG = "valider_paiement_niveau1_{$slug}";

    // Vérification permission via rôle
    $hasPermission = $user->role
        ? $user->role->permissions->pluck('nom')->contains($permissionDG)
        : false;

    if (!$hasPermission) {
        return redirect()->route('paiements.encours')
            ->with('error', "Vous n'avez pas la permission d'accéder à ce paiement.");
    }

    //  IMPORTANT : récupérer le versement en attente
    $versementEnCours = $paiement->paiementVersements
        ->where('statut_versement', 'en_attente')
        ->sortByDesc('created_at')
        ->first();

    // Calcul des montants
    $montantDejaPaye = $paiement->paiementVersements
        ->where('statut_versement', 'valide')
        ->sum('montant');

    $montantRestant = $paiement->demande->montant_paiement_fournisseur - $montantDejaPaye;

    $historiqueVersements = $paiement->paiementVersements
        ->sortByDesc('created_at');

    return view('paiements.dg_valider', [
        'paiement' => $paiement,
        'montantDejaPaye' => $montantDejaPaye,
        'montantRestant' => $montantRestant,
        'historiqueVersements' => $historiqueVersements,
        'versementEnCours' => $versementEnCours 
    ]);
}
public function showPaiement($id)
{
    // Récupérer le paiement avec sa demande et ses versements
    $paiement = Paiement::with(['demande', 'paiementVersements'])->find($id);

    if (!$paiement) {
        return redirect()->back()->with('error', "Paiement introuvable.");
    }

    // Recalculer montants et statut
    $this->recalculerMontantsEtStatut($paiement);

    // Vérifier s'il y a un versement en attente
    $versementEnAttente = $paiement->paiementVersements()
        ->where('statut_versement', 'en_attente')
        ->exists();

    // Afficher la vue comme dans search()
    return view('paiements.show_paiement', [
        'demande' => $paiement->demande,
        'paiement' => $paiement,
        'montantTotal' => $paiement->montant_prevu,
        'montantDejaPaye' => $paiement->montant_paye,
        'montantRestant' => $paiement->montant_restant,
        'versementEnAttente' => $versementEnAttente,
        'error' => null,
    ]);
}

}
