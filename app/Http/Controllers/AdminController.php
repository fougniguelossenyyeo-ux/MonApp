<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entite;
use App\Models\User;
use App\Models\Paiement;
use App\Models\Demande;
use App\Models\PaiementVersement;
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
