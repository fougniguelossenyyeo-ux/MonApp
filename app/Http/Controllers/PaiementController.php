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

class PaiementController extends Controller
{
    private $statutsPaiement = [
        0 => 'Non initié',
        1 => 'En cours',
        2 => 'Partiellement payé',
        3 => 'Payé',
        -3 => 'Refusé'
    ];

    /** Recalcul des montants et du statut global */
    private function recalculerMontantsEtStatut(Paiement $paiement)
    {
        $montantValide = $paiement->paiementsVersements()
            ->where('statut_versement', 'valide')
            ->sum('montant');

        $paiement->montant_deja_paye = $montantValide;
        $paiement->montant_restant = max(0, $paiement->montant_a_payer - $montantValide);

        // Mise à jour du statut selon les versements
        $this->mettreAJourStatutPaiement($paiement);
    }

    /** Mettre à jour le statut d'un paiement */
    public function mettreAJourStatutPaiement(Paiement $paiement)
    {
        $montantDejaPaye = $paiement->paiementsVersements
            ->where('statut_versement', 'valide')
            ->sum('montant');

        $versementsEnAttente = $paiement->paiementsVersements
            ->where('statut_versement', 'en_attente');

        $montantRestant = max(0, $paiement->montant_a_payer - $montantDejaPaye);

        if ($montantRestant == 0) {
            $paiement->status_paiement = 3; // Payé
        } elseif ($montantDejaPaye > 0) {
            $paiement->status_paiement = $versementsEnAttente->isNotEmpty() ? 1 : 2; // En cours ou Partiellement payé
        } else {
            $paiement->status_paiement = $versementsEnAttente->isNotEmpty() ? 1 : 0; // En cours ou Non initié
        }

        $paiement->save();
    }

    /** Page principale */
    public function index()
    {
        return view('paiements.faire_paiement', [
            'paiements' => collect(),
            'demande' => null,
            'paiement' => null,
            'statuts' => $this->statutsPaiement,
            'montantTotal' => 0,
            'montantDejaPaye' => 0,
            'montantRestant' => 0,
            'error' => null
        ]);
    }

    /** Recherche d'une demande */
    public function search(Request $request)
    {
        $reference = $request->input('reference_dp');
        $demande = Demande::where('reference_dp', $reference)->first();

        if (!$demande) {
            return view('paiements.faire_paiement', [
                'error' => "Aucune demande trouvée avec cette référence.",
                'demande' => null,
                'paiement' => null,
                'statuts' => $this->statutsPaiement,
                'montantTotal' => 0,
                'montantDejaPaye' => 0,
                'montantRestant' => 0
            ]);
        }

        if ($demande->status != 3) {
            $montantTotal = $demande->montant_paiement_fournisseur ?? 0;
            return view('paiements.faire_paiement', [
                'error' => "Cette demande n'est pas encore validée.",
                'demande' => $demande,
                'paiement' => null,
                'statuts' => $this->statutsPaiement,
                'montantTotal' => $montantTotal,
                'montantDejaPaye' => 0,
                'montantRestant' => $montantTotal
            ]);
        }

        $montantTotal = $demande->montant_paiement_fournisseur ?? 0;

        // Créer ou récupérer le paiement
        $paiement = Paiement::firstOrCreate(
            ['demande_id' => $demande->id],
            [
                'montant_deja_paye' => 0,
                'montant_a_payer' => $montantTotal,
                'montant_restant' => $montantTotal,
                'status_paiement' => 1 // En cours dès le lancement
            ]
        );

        $this->recalculerMontantsEtStatut($paiement);

        return view('paiements.faire_paiement', [
            'demande' => $demande,
            'paiement' => $paiement,
            'error' => null,
            'statuts' => $this->statutsPaiement,
            'montantTotal' => $paiement->montant_a_payer,
            'montantDejaPaye' => $paiement->montant_deja_paye,
            'montantRestant' => $paiement->montant_restant,
            'nombreVersements' => $paiement->paiementsVersements()->count()
        ]);
    }

    /** Enregistrer un nouveau versement */
    public function payer(Request $request, $paiementId)
    {
        $paiement = Paiement::with('demande')->findOrFail($paiementId);

        if ($paiement->status_paiement == 3) {
            return back()->with('error', 'Paiement déjà complètement payé.');
        }

        if ($paiement->status_paiement == -3) {
            return back()->with('error', 'Paiement refusé, impossible de verser.');
        }

        $montant = floatval($request->input('montant'));
        $commentaire = $request->input('commentaire');

        if ($montant <= 0) {
            return back()->with('error', 'Le montant doit être supérieur à zéro.');
        }

        $montantDejaPaye = $paiement->paiementsVersements()
            ->where('statut_versement', 'valide')
            ->sum('montant');

        $montantRestant = $paiement->montant_a_payer - $montantDejaPaye;

        if ($montant > $montantRestant) {
            return back()->with('error', 'Le montant dépasse le reste à payer.');
        }

        PaiementVersement::create([
            'id' => Str::uuid(),
            'paiement_id' => $paiement->id,
            'montant' => $montant,
            'commentaire' => $commentaire,
            'date_versement' => now(),
            'statut_versement' => 'en_attente'
        ]);

        $this->recalculerMontantsEtStatut($paiement);

        $this->notifierDG($paiement);

        return back()->with('success', "Versement de {$montant} F CFA enregistré (en attente de validation DG).");
    }

    /** Validation DG d’un versement */
public function validerDG($id)
{
    $paiement = Paiement::with('paiementsVersements', 'demande.user')->findOrFail($id);

    // Récupérer le dernier versement en attente
    $dernierVersement = $paiement->paiementsVersements()
        ->where('statut_versement', 'en_attente')
        ->latest('created_at')
        ->first();

    if (!$dernierVersement) {
        return back()->with('error', 'Aucun versement en attente à valider.');
    }

    // Valider le versement
    $dernierVersement->update(['statut_versement' => 'valide']);

    // Recalculer montants et statut global
    $this->recalculerMontantsEtStatut($paiement);

    // Envoyer le mail à l'initiateur
    Mail::to($paiement->demande->user->email)
        ->send(new PaiementValideMail($paiement, $dernierVersement));

    return back()->with('success', "Versement de {$dernierVersement->montant} F CFA validé et email envoyé à l'initiateur.");
}


    /** Refus DG d’un versement */
    public function refuserDG($id)
    {
        $paiement = Paiement::with(['paiementsVersements', 'demande.user'])->findOrFail($id);

        $dernierVersement = $paiement->paiementsVersements()
            ->where('statut_versement', 'en_attente')
            ->latest('created_at')
            ->first();

        if (!$dernierVersement) {
            return back()->with('error', 'Aucun versement en attente à refuser.');
        }

        $dernierVersement->update(['statut_versement' => 'refuse']);

        $this->recalculerMontantsEtStatut($paiement);

        if ($paiement->demande && $paiement->demande->user) {
            Mail::to($paiement->demande->user->email)->send(new PaiementRefuseMail($paiement));
        }

        return back()->with('error', 'Versement refusé par le DG. L’initiateur a été notifié.');
    }

    /** Notification DG */
    private function notifierDG(Paiement $paiement)
    {
        $roleDG = Role::where('libelle', 'DG')
            ->where('entite_id', $paiement->demande->entite_id)
            ->first();

        if (!$roleDG) return;

        $dgList = User::where('role_id', $roleDG->id)->get();
        foreach ($dgList as $dg) {
            Mail::to($dg->email)->send(new PaiementAValiderMail($paiement));
        }
    }

    /** Affichage détail d’un paiement */
    public function show($id)
    {
        $paiement = Paiement::with(['demande', 'paiementsVersements'])->findOrFail($id);

        return view('paiements.show_paiement', [
            'paiement' => $paiement,
            'montantTotal' => $paiement->montant_a_payer,
            'montantDejaPaye' => $paiement->montant_deja_paye,
            'montantRestant' => $paiement->montant_restant
        ]);
    }

    /** Paiements émis (partiels ou soldés) */
    public function emis()
    {
        $paiements = Paiement::with('demande')
            ->whereIn('status_paiement', [2, 3])
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('paiements.emis', compact('paiements'));
    }

    /** Paiements en cours (attente DG, demandes validées) */
    public function encours()
    {
        $paiements = Paiement::with(['demande', 'paiementsVersements'])
            ->whereHas('paiementsVersements', function ($query) {
                $query->where('statut_versement', 'en_attente');
            })
            ->whereHas('demande', function ($query) {
                $query->where('status', 3); // demande validée
            })
            ->latest()
            ->paginate(12);

        foreach ($paiements as $paiement) {
            $paiement->montantDejaPaye = $paiement->paiementsVersements()
                ->where('statut_versement', 'valide')
                ->sum('montant');
            $paiement->montantRestant = max(0, $paiement->montant_a_payer - $paiement->montantDejaPaye);

            $paiement->versementsEnAttente = $paiement->paiementsVersements()
                ->where('statut_versement', 'en_attente')
                ->get();
        }

        return view('paiements.encours', compact('paiements'));
    }

    /** Paiements partiellement payés */
    public function partiellement()
    {
        $paiements = Paiement::with('demande')
            ->where('status_paiement', 2)
            ->latest()
            ->paginate(12);

        return view('paiements.partiellement', compact('paiements'));
    }

    /** Paiements soldés */
    public function valides()
    {
        $paiements = Paiement::with('demande')
            ->where('status_paiement', 3)
            ->latest()
            ->paginate(12);

        return view('paiements.valides', compact('paiements'));
    }

    /** Affiche le paiement à valider par le DG avec l'historique des versements */
    public function dgValider($id)
{
    $paiement = Paiement::with(['demande.user', 'paiementsVersements'])->findOrFail($id);

    // Mettre à jour le statut dans la base
    $this->mettreAJourStatutPaiement($paiement);

    // Vérifier que le paiement est toujours en cours
    if ($paiement->status_paiement != 1) { // 1 = En cours
        return redirect()->route('paiements.encours')
            ->with('error', 'Vous ne pouvez pas accéder à ce paiement car il n’est plus en cours.');
    }

    $historiqueVersements = $paiement->paiementsVersements->sortBy('date_versement');

    $montantDejaPaye = $paiement->paiementsVersements
        ->where('statut_versement', 'valide')
        ->sum('montant');

    $montantRestant = max(0, $paiement->montant_a_payer - $montantDejaPaye);

    return view('paiements.dg_valider', [
        'paiement' => $paiement,
        'montantDejaPaye' => $montantDejaPaye,
        'montantRestant' => $montantRestant,
        'historiqueVersements' => $historiqueVersements,
    ]);
}
public function shown($id)
{
    $paiement = Paiement::with(['demande.user', 'paiementsVersements'])->findOrFail($id);

    // Vérifier que le paiement est uniquement partiellement payé
    if ($paiement->status_paiement != 2) { // 2 = Partiellement payé
        return redirect()->route('paiements.partiellement')
            ->with('error', 'Vous ne pouvez accéder à ce paiement que s’il est partiellement payé.');
    }

    $demande = $paiement->demande;

    // Montants
    $montantDejaPaye = $paiement->paiementsVersements
        ->where('statut_versement', 'valide')
        ->sum('montant');

    $montantTotal = $paiement->montant_a_payer;
    $montantRestant = max(0, $montantTotal - $montantDejaPaye);

    // Historique des versements
    $historiqueVersements = $paiement->paiementsVersements->sortBy('created_at');

    return view('paiements.show', [
        'paiement' => $paiement,
        'demande' => $demande,
        'montantTotal' => $montantTotal,
        'montantDejaPaye' => $montantDejaPaye,
        'montantRestant' => $montantRestant,
        'historiqueVersements' => $historiqueVersements,
    ]);
}


}
