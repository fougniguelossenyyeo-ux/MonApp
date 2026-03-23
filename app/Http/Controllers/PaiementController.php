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

class PaiementController extends Controller
{
   
/** Recalcul des montants et du statut global */
 private function recalculerMontantsEtStatut(Paiement $paiement)
    {
        $paiement->montant_paye = $paiement->montantDejaPaye();
        $paiement->montant_restant = max(0, $paiement->montant_prevu - $paiement->montant_paye);

        // Statut
        if ($paiement->montant_restant == 0) {
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
        $reference = $request->input('reference_dp');

        $demande = Demande::where('reference_dp', $reference)
            ->where('status', 3)
            ->first();

        if (!$demande) {
            return redirect()->back()->with('error', "Aucune demande trouvée ou non validée.");
        }

        $paiement = Paiement::with('paiementVersements')
            ->where('demande_id', $demande->id)
            ->first();

        //  Sécurité (au cas où)
    if (!$paiement) {
        return redirect()->back()->with('error', "Paiement introuvable pour cette demande.");
    }

        $this->recalculerMontantsEtStatut($paiement);

        $versementEnAttente = $paiement->paiementVersements()
            ->where('statut_versement', 'en_attente')
            ->exists();

        return view('paiements.show', [
            'demande' => $demande,
            'paiement' => $paiement,
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
    $paiement = Paiement::with('paiementVersements', 'demande.entite')->findOrFail($paiementId);

    if ($paiement->statut === 'termine') {
        return back()->with('error', 'Paiement déjà complètement payé.');
    }

    // Vérifier qu'il existe un responsable ayant la permission
    $entiteSlug = Str::slug($paiement->demande->entite->libelle_entite, '_');
    $responsable = User::whereHas('role.permissions', function ($q) use ($entiteSlug) {
        $q->where('nom', "valider_paiement_niveau1_$entiteSlug");
    })->first();

    if (!$responsable) {
        return back()->with('error', "Impossible de faire le paiement : aucun responsable ne possède la permission  Veuillez demander à l'administrateur d'attribuer la permission.");
    }

    $montant = floatval($request->input('montant'));
    $commentaire = $request->input('commentaire');

    if ($montant <= 0) {
        return back()->with('error', 'Le montant doit être supérieur à zéro.');
    }

    $montantRestant = $paiement->montantRestant();

    if ($montant > $montantRestant) {
        return back()->with('error', 'Le montant dépasse le reste à payer.');
    }

    // Création du versement
    $versement = PaiementVersement::create([
        'id' => Str::uuid(),
        'paiement_id' => $paiement->id,
        'montant' => $montant,
        'commentaire' => $commentaire,
        'date_versement' => now(),
        'statut_versement' => 'en_attente',
    ]);

    // Recalcul des montants et statut du paiement
    $this->recalculerMontantsEtStatut($paiement);

    // Envoyer le mail au responsable
    Mail::to($responsable->email)
        ->send(new PaiementAValiderMail($paiement, $versement));

    return back()->with('success', "Versement de " . number_format($montant, 0, ',', ' ') . " F CFA enregistré et notification envoyée au responsable.");
}

/** ─────────────────────────────────────────────
     * Validation DG d’un versement
     * ───────────────────────────────────────────── */
   public function validerDG($paiementId)
{
    $paiement = Paiement::with('paiementVersements', 'demande.user', 'demande.entite')->findOrFail($paiementId);

    $user = auth()->user(); // Utilisateur connecté
    $entiteSlug = Str::slug($paiement->demande->entite->libelle_entite, '_');
    $permissionRequise = "valider_paiement_niveau1_$entiteSlug";

    // Vérifier si l'utilisateur a la permission sur l'entité
    if (!$user->hasPermissionTo($permissionRequise)) {
        return back()->with('error', "Vous n'avez pas la permission de valider ce paiement pour cette entité.");
    }

    // Récupérer le dernier versement en attente
    $dernierVersement = $paiement->paiementVersements()
        ->where('statut_versement', 'en_attente')
        ->latest('created_at')
        ->first();

    if (!$dernierVersement) {
        return back()->with('error', 'Aucun versement en attente à valider.');
    }

    // Valider le versement
    $dernierVersement->update(['statut_versement' => 'valide']);

    // Recalculer les montants et le statut global du paiement
    $this->recalculerMontantsEtStatut($paiement);

    // Envoyer un mail à l'initiateur
    Mail::to($paiement->demande->user->email)
        ->send(new PaiementValideMail($paiement, $dernierVersement));

    return back()->with('success', "Versement de {$dernierVersement->montant} F CFA validé et email envoyé à l'initiateur.");
}


   /** ─────────────────────────────────────────────
     * Refus DG d’un versement
     * ───────────────────────────────────────────── */
    public function refuserDG($paiementId)
    {
        $paiement = Paiement::with('paiementVersements', 'demande.user')->findOrFail($paiementId);

        $dernierVersement = $paiement->paiementVersements()
            ->where('statut_versement', 'en_attente')
            ->latest('created_at')
            ->first();

        if (!$dernierVersement) {
            return back()->with('error', 'Aucun versement en attente à refuser.');
        }

        $dernierVersement->update(['statut_versement' => 'refuse']);
        $this->recalculerMontantsEtStatut($paiement);

        Mail::to($paiement->demande->user->email)
            ->send(new PaiementRefuseMail($paiement));

        return back()->with('error', 'Versement refusé par le DG. L’initiateur a été notifié.');
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
    $paiements = Paiement::with([
            'demande.entite',
            'demande.user',
            'paiementVersements' => function ($q) {
                $q->latest();
            }
        ])
        ->whereHas('paiementVersements', function ($q) {
            $q->where('statut_versement', 'en_attente');
        })
        ->whereHas('demande', function ($q) {
            $q->where('status', 3);
        })
        ->latest()
        ->paginate(12);

    //  Calculs sans requêtes supplémentaires
    $paiements->getCollection()->transform(function ($paiement) {

        $paiement->montant_deja_paye = $paiement->montantDejaPaye();
        $paiement->montant_restant = $paiement->montantRestant();

        // Filtrer depuis la collection déjà chargée
        $paiement->versementsEnAttente = $paiement->paiementVersements
            ->where('statut_versement', 'en_attente');

        return $paiement;
    });

    // Statistiques globales
    $totalEmis = Paiement::count();
    $totalEncours = Paiement::where('statut', 'en_attente')->count();
    $totalPartiels = Paiement::where('statut', 'partiel')->count();
    $totalValide = Paiement::where('statut', 'termine')->count();

    return view('paiements.encours', compact(
        'paiements',
        'totalEmis',
        'totalEncours',
        'totalPartiels',
        'totalValide'
    ));
}

    /** ─────────────────────────────────────────────
     * Liste des paiements partiellement payés
     * ───────────────────────────────────────────── */
   public function partiellement()
{
    $paiements = Paiement::with([
            'demande.entite',
            'demande.user',
            'paiementVersements' => function ($q) {
                $q->latest();
            }
        ])
        ->whereIn('statut', ['en_attente', 'partiel']) //  logique correcte
        ->latest()
        ->paginate(12);

    //  Calculs optimisés
    $paiements->getCollection()->transform(function ($paiement) {

        $paiement->montant_deja_paye = $paiement->montantDejaPaye();
        $paiement->montant_restant = $paiement->montantRestant();

        return $paiement;
    });

    //  Stats (comme ailleurs)
    $totalEmis = Paiement::count();
    $totalEncours = Paiement::where('statut', 'en_attente')->count();
    $totalPartiels = Paiement::where('statut', 'partiel')->count();
    $totalValide = Paiement::where('statut', 'termine')->count();

    return view('paiements.partiellement', compact(
        'paiements',
        'totalEmis',
        'totalEncours',
        'totalPartiels',
        'totalValide'
    ));
}

    /** ─────────────────────────────────────────────
     * Liste des paiements soldés
     * ───────────────────────────────────────────── */
   public function valides()
{
    $paiements = Paiement::with([
            'demande',
            'paiementVersements' => function ($q) {
                $q->latest();
            }
        ])
        ->where('statut', 'termine') //  filtrage direct en base
        ->latest()
        ->paginate(12);

    // Calculs (optionnel mais recommandé pour cohérence avec les autres pages)
    foreach ($paiements as $paiement) {
        $paiement->montant_paye = $paiement->montantDejaPaye();
        $paiement->montant_restant = $paiement->montantRestant();
    }

    // Stats pour le header (important sinon erreur dans la vue)
    $totalEmis = Paiement::count();
    $totalEncours = Paiement::where('statut', 'en_attente')->count();
    $totalPartiels = Paiement::where('statut', 'partiel')->count();
    $totalValide = Paiement::where('statut', 'termine')->count();

    return view('paiements.valides', compact(
        'paiements',
        'totalEmis',
        'totalEncours',
        'totalPartiels',
        'totalValide'
    ));
}


// Liste de tous les paiements émis (tous statuts confondus)

public function emis()
{
    // Récupération des paiements avec relations
    $paiements = Paiement::with(['demande', 'paiementVersements'])
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    // ==============================
    // STATISTIQUES
    // ==============================

    $totalEmis = Paiement::count();

    $totalEncours = Paiement::where('statut', 'en_attente')->count();

    $totalPartiels = Paiement::where('statut', 'partiel')->count();

    $totalValide = Paiement::where('statut', 'termine')->count();

    // ==============================
    // Adapter les champs pour la vue
    // ==============================

    $paiements->getCollection()->transform(function ($p) {

        // Mapping statut vers status_paiement (pour ta vue)
        $p->status_paiement = match ($p->statut) {
            'en_attente' => 1,
            'partiel' => 2,
            'termine' => 3,
            default => 0,
        };

        // Harmonisation des noms utilisés dans la vue
        $p->montant_a_payer = $p->montant_prevu;
        $p->montant_deja_paye = $p->montant_paye;

        return $p;
    });

    // ==============================
    // Retour vue
    // ==============================

    return view('paiements.emis', [
        'paiements' => $paiements,
        'totalEmis' => $totalEmis,
        'totalEncours' => $totalEncours,
        'totalPartiels' => $totalPartiels,
        'totalValide' => $totalValide,
    ]);
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
    // Récupérer le paiement avec les relations nécessaires
    $paiement = Paiement::with([
        'paiementVersements',
        'demande.user',
        'demande.entite'
    ])->findOrFail($paiementId);

    $user = auth()->user();
    $slug = Str::slug($paiement->demande->entite->libelle_entite, '_');
    $permissionDG = "valider_paiement_niveau1_{$slug}";

    // Vérifier si l'utilisateur a la permission via son rôle
    $hasPermission = $user->role
        ? $user->role->permissions->pluck('nom')->contains($permissionDG)
        : false;

    if (!$hasPermission) {
        return redirect()->route('paiements.encours')
                         ->with('error', "Vous n'avez pas la permission d'accéder à ce paiement.");
    }

    // Calcul des montants
    $montantDejaPaye = $paiement->paiementVersements
        ->where('statut_versement', 'valide')
        ->sum('montant');

    $montantRestant = $paiement->demande->montant_paiement_fournisseur - $montantDejaPaye;

    $historiqueVersements = $paiement->paiementVersements->sortByDesc('created_at');

    return view('paiements.dg_valider', [
        'paiement' => $paiement,
        'montantDejaPaye' => $montantDejaPaye,
        'montantRestant' => $montantRestant,
        'historiqueVersements' => $historiqueVersements,
    ]);
}

}
