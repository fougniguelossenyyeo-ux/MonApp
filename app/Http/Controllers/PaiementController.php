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

class PaiementController extends Controller
{
    // Statuts de paiement
    private $statutsPaiement = [
        0 => 'Non initié',
        1 => 'En cours',
        2 => 'Partiellement payé',
        3 => 'Payé'
    ];

    // Page principale pour initier un paiement
    public function index()
    {
        $paiements = collect();
        $demande = null;

        // Passer toutes les variables même si vides pour éviter les erreurs
        return view('paiements.faire_paiement', [
            'paiements' => $paiements,
            'demande' => $demande,
            'paiement' => null,
            'statuts' => $this->statutsPaiement,
            'montantTotal' => 0,
            'montantDejaPaye' => 0,
            'montantRestant' => 0,
            'error' => null
        ]);
    }

    // Recherche d'une demande
    public function search(Request $request)
    {
        $reference = $request->input('reference_dp');
        $demande = Demande::where('reference_dp', $reference)->first();

        // Cas : demande non trouvée
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

        // Cas : demande non validée
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

        // Création ou récupération du paiement
        $montantTotal = $demande->montant_paiement_fournisseur ?? 0;
        $paiement = Paiement::firstOrCreate(
            ['demande_id' => $demande->id],
            [
                'montant_deja_paye' => 0,
                'montant_a_payer'   => $montantTotal,
                'montant_restant'   => $montantTotal,
                'status_paiement'   => 0
            ]
        );

        return view('paiements.faire_paiement', [
            'demande' => $demande,
            'paiement' => $paiement,
            'error' => null,
            'statuts' => $this->statutsPaiement,
            'montantTotal' => $montantTotal,
            'montantDejaPaye' => $paiement->montant_deja_paye,
            'montantRestant' => $paiement->montant_restant
        ]);
    }

    // Effectuer un versement
    public function payer(Request $request, $paiementId)
    {
        $paiement = Paiement::with('paiementVersements', 'demande')->findOrFail($paiementId);

        $montant = floatval($request->input('montant_a_payer'));
        $commentaire = $request->input('commentaire') ?? null;

        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Le montant doit être supérieur à zéro.');
        }

        if ($montant > $paiement->montant_restant) {
            return redirect()->back()->with('error', 'Le montant dépasse le reste à payer.');
        }

        // Nombre de versements effectués
        $nombreVersements = $paiement->paiementVersements()->count() + 1;

        // Enregistrer le versement
        PaiementVersement::create([
            'id' => Str::uuid(),
            'paiement_id' => $paiement->id,
            'montant' => $montant,
            'commentaire' => $commentaire,
            'nombre_versements' => $nombreVersements,
            'date_versement' => now(),
        ]);

        // Mettre à jour le paiement
        $paiement->montant_deja_paye += $montant;
        $paiement->montant_restant = max(0, $paiement->montant_a_payer - $paiement->montant_deja_paye);

        $paiement->status_paiement = $this->calculerStatutPaiement($paiement);
        $paiement->save();

        // Notification DG
        $this->notifierDG($paiement);

        return redirect()->back()->with('success', "Versement de {$montant} F CFA enregistré avec succès.");
    }

    // Statut du paiement
    private function calculerStatutPaiement(Paiement $paiement)
    {
        if ($paiement->montant_restant <= 0) return 3; // Payé
        if ($paiement->montant_deja_paye > 0) return 2; // Partiellement payé
        return 1; // En cours
    }

    // Notifier DG par mail
    private function notifierDG(Paiement $paiement)
    {
        $roleDG = Role::where('libelle', 'DG')
            ->where('entite_id', $paiement->demande->entite_id)
            ->first();

        if (!$roleDG) return;

        $dg = User::where('role_id', $roleDG->id)->first();
        if ($dg) {
            Mail::to($dg->email)->send(new PaiementAValiderMail($paiement));
        }
    }

    // Pages d'affichage des paiements
    public function emis()
    {
        Paiement::where('status_paiement', 2)
            ->where('montant_restant', '<=', 0)
            ->update(['status_paiement' => 3]);

        $paiements = Paiement::with('demande')
            ->whereIn('status_paiement', [2, 3])
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('paiements.emis', compact('paiements'));
    }

    public function encours()
    {
        $paiements = Paiement::with('demande')
            ->where('status_paiement', 1)
            ->latest()
            ->paginate(12);

        return view('paiements.encours', compact('paiements'));
    }

    public function partiellement()
    {
        $paiements = Paiement::with('demande')
            ->where('status_paiement', 2)
            ->latest()
            ->paginate(12);

        return view('paiements.partiellement', compact('paiements'));
    }

    public function valides()
    {
        $paiements = Paiement::with('demande')
            ->where('status_paiement', 3)
            ->latest()
            ->paginate(12);

        return view('paiements.valides', compact('paiements'));
    }

    // Validation DG
    public function dgValider($id)
    {
        $paiement = Paiement::with(['demande', 'demande.user', 'demande.entite'])->findOrFail($id);
        return view('paiements.dg_valider', compact('paiement'));
    }

    public function validerDg($id)
    {
        $paiement = Paiement::findOrFail($id);
        $paiement->status_paiement = $this->calculerStatutPaiement($paiement);
        $paiement->save();

        return redirect()->route('paiements.encours')->with('success', 'Paiement validé par le DG.');
    }

    public function refuserDg($id)
    {
        $paiement = Paiement::findOrFail($id);
        $paiement->status_paiement = -3;
        $paiement->save();

        return redirect()->route('paiements.encours')->with('error', 'Paiement refusé par le DG.');
    }
}
