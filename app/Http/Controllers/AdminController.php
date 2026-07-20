<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Entite;
use App\Models\Paiement;
use App\Models\PaiementVersement;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $entites = Entite::all();
        $users = User::all();
        $paiements = Paiement::all();
        $demandes = Demande::all();
        $versements = PaiementVersement::all();

        return view('dashboard', compact('entites', 'users', 'paiements', 'demandes', 'versements'));
    }
}
