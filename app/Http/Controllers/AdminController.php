<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\Entite;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $entites = Entite::all();
        $selectedEntite = $request->get('entite', '');

        // 🔹 Filtrer les demandes selon l'entité sélectionnée ou toutes si vide
        $demandesQuery = Demande::with('entite');

        if ($selectedEntite) {
            $demandesQuery->whereHas('entite', function($q) use ($selectedEntite) {
                $q->where('libelle_entite', $selectedEntite);
            });
        }

        $demandesRecentes = $demandesQuery->orderBy('created_at', 'desc')->limit(10)->get();

        // 🔹 Calcul des statistiques globales sur les demandes filtrées
        $demandes = $demandesQuery->get(); // toutes les demandes pour le calcul

        $totalDemandes = $demandes->count();
        $enAttente    = $demandes->whereIn('status', [0,1,2])->count();
        $validees     = $demandes->where('status', 3)->count();
        $refusees     = $demandes->whereIn('status', [-1,-2,-3])->count();
        $montantTotal = $demandes->sum('montant_paiement_fournisseur');

        // 🔹 Montant payé et impayé liés aux demandes affichées
        $paiementsQuery = Paiement::whereIn('demande_id', $demandes->pluck('id'));
        $montantPaye = $paiementsQuery->sum('montant_deja_paye');
        $montantImpayé = $montantTotal - $montantPaye;

        return view('dashboard', [
            'demandesRecentes' => $demandesRecentes,
            'entites' => $entites,
            'selectedEntite' => $selectedEntite,
            'totalDemandes' => $totalDemandes,
            'enAttente' => $enAttente,
            'validees' => $validees,
            'refusees' => $refusees,
            'montantTotal' => $montantTotal,
            'montantPaye' => $montantPaye,
            'montantImpayé' => $montantImpayé,
        ]);
    }
}
