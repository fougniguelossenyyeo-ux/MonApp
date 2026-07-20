<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\HistoriqueAction;
use App\Models\PaiementVersement;
use App\Models\User;
use Illuminate\Http\Request;

class HistoriqueActionController extends Controller
{
    /**
     * Affiche la liste de tous les historiques d'action
     */
    public function index()
    {
        $historiques = HistoriqueAction::with([
            'user',
            'entite',
        ])
            ->latest()
            ->paginate(10);

        $users = User::orderBy('nom')->get();

        return view('historiques.index', compact('historiques', 'users'));
    }

    /**
     * Filtrage des historiques
     */
    public function filter(Request $request)
    {
        $query = HistoriqueAction::with([
            'user',
            'entite',
        ]);

        //  Filtre utilisateur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        //  Filtre modèle (polymorphique)
        if ($request->filled('subject_type')) {

            if ($request->subject_type === 'Demande') {
                $query->where('subject_type', Demande::class);
            }

            if ($request->subject_type === 'PaiementVersement') {
                $query->where('subject_type', PaiementVersement::class);
            }
        }

        //  Filtre date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $historiques = $query->latest()->paginate(10);

        $users = User::orderBy('nom')->get();

        return view('historiques.index', compact('historiques', 'users'));
    }
}
