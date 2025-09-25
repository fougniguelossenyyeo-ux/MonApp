<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

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
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Connexion réussie 🎉'); 
        }

        return back()->with('error', 'Identifiants incorrects ❌')->onlyInput('email');
    }

    // Afficher le formulaire d’inscription
    public function showRegisterForm()
    {
        $roles = Role::with('entite')->get();
        return view('auth.register', compact('roles'));
    }

    // Traiter l'inscription
    public function register(Request $request)
    {
      
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'poste' => 'nullable|string|max:255',
            'fonction' => 'nullable|string|max:255',
            'role_id' => 'required|string|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'poste' => $validated['poste'] ?? null,
            'fonction' => $validated['fonction'] ?? null,
            'role_id' => $validated['role_id'],
            'password' => Hash::make($validated['password']),
        ]);

        if($user) {
            return redirect()->route('users.list')->with('success', 'Inscription réussie');
        } else {
            return back()->with('error', 'Erreur lors de l’enregistrement')->withInput();
        }
    }

    // Liste des utilisateurs
    public function listregister()
    {
        $users = User::with('role.entite')->orderBy('nom')->get();
        return view('auth.list', compact('users'));
    }

    // Afficher le formulaire d’édition d’un utilisateur
    public function edit($id)
    {
        $user = User::with('role.entite')->findOrFail($id);
        $roles = Role::with('entite')->get();

        return view('auth.edit', compact('user', 'roles'));
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        // Récupérer l'utilisateur avant la validation
    $user = User::findOrFail($id);


        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'poste' => 'nullable|string|max:255',
            'fonction' => 'nullable|string|max:255',
            'role_id' => 'required|string|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->poste = $validated['poste'] ?? null;
        $user->fonction = $validated['fonction'] ?? null;
        $user->role_id = $validated['role_id'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.list')->with('success', 'Utilisateur mis à jour avec succès');
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.list')->with('success', 'Utilisateur supprimé');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnexion réussie');
    }
}
