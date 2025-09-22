<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Afficher la page de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Traiter la connexion
    public function login(Request $request)
    {
        // Validation
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        // Tentative de connexion
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')->with('succes', 'connexion reussie'); // après connexion
        }

        // Échec
        return back()->withErrors([
            'email' => 'Identifiants incorrects',
        ])->onlyInput('email');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnexion reussiegi');
    }
}
