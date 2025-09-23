<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
    // Validation
    $credentials = $request->validate([
        'email' => ['required','email'],
        'password' => ['required'],
    ]);

    // Tentative de connexion
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->intended('/dashboard')->with('success', 'Connexion réussie 🎉'); 
    }

  // Échec
        return back()->with('error', 'Identifiants incorrects ❌')
                     ->onlyInput('email');
    }
    // Afficher le formulaire d’inscription
public function showRegisterForm()
{
    return view('auth.register');
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
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'nom' => $validated['nom'],
        'prenom' => $validated['prenom'],
        'email' => $validated['email'],
        'poste' => $validated['poste'] ?? null,
        'fonction' => $validated['fonction'] ?? null,
        'password' => Hash::make($validated['password']),
    ]);

    // Supprime la connexion auto si tu veux juste lister
    // Auth::login($user);

    // Redirection correcte vers la liste des utilisateurs
    return redirect()->route('users.list')->with('success', 'Inscription réussie');
}
    
   public function listregister()
{
    $users = User::orderBy('nom')->get(); // tri par nom
    return view('auth.list', compact('users')); // ici auth.list
}
 // Afficher le formulaire d’édition
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('auth.edit', compact('user'));
    }
    
   
    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'poste' => 'nullable|string|max:255',
            'fonction' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->poste = $validated['poste'] ?? null;
        $user->fonction = $validated['fonction'] ?? null;

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

        return redirect('/')->with('success', 'Déconnexion reussie');
    }

}
