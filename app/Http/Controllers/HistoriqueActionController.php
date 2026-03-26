<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriqueAction;
use App\Models\User;
use App\Models\Demande;
use App\Models\PaiementVersement;
class HistoriqueActionController extends Controller
{
    /**
     * Affiche la liste de tous les historiques d'action
     */
public function index()
{
    // Récupérer les historiques avec les relations nécessaires
    $historiques = HistoriqueAction::with([
        'user',
        'entite',
        'subject.paiement.demande'
    ])->latest()->paginate(10);

    // Récupérer tous les utilisateurs pour le filtre
    $users = User::orderBy('nom')->get();

    // Retourner la vue avec historiques et utilisateurs
    return view('historiques.index', compact('historiques', 'users'));
}
public function filter(Request $request) 
{
    $query = HistoriqueAction::with([
        'user',
        'entite',
        'subject.paiement.demande'
    ]);

    //  Filtre utilisateur
    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }

    //  Filtre modèle
    if ($request->subject_type) {

        if ($request->subject_type == 'DemandePaiement') {
            $query->where('subject_type', 'Demande');
        }

        if ($request->subject_type == 'PaiementVersement') {
            $query->where('subject_type', 'PaiementVersement');
        }
    }

    //  Filtre date
    if ($request->date_from) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->date_to) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $historiques = $query->latest()->paginate(10);

    $users = User::orderBy('nom')->get();

    return view('historiques.index', compact('historiques', 'users'));
}
}
