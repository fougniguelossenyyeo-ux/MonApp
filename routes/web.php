<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EntiteController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PermissionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Page d’accueil
Route::get('/', function () {
    return view('auth.login');
});

// Mot de passe oublié
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Auth
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register.form');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/deconnexion', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USERS (protégé maintenant)
    |--------------------------------------------------------------------------
    */
    Route::get('/users', [AuthController::class, 'listregister'])->name('users.list');
    Route::get('/users/{id}/edit', [AuthController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AuthController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AuthController::class, 'destroy'])->name('users.destroy');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');


    /*
    |--------------------------------------------------------------------------
    | ENTITES
    |--------------------------------------------------------------------------
    */
    Route::get('/entites', [EntiteController::class, 'indexe'])->name('entites.index');
    Route::get('/entites/create', [EntiteController::class, 'create'])->name('entites.create');
    Route::post('/entites', [EntiteController::class, 'store'])->name('entites.store');
    Route::get('/entites/{entite}/edit', [EntiteController::class, 'edit'])->name('entites.edit');
    Route::put('/entites/{entite}', [EntiteController::class, 'update'])->name('entites.update');
    Route::delete('/entites/{entite}', [EntiteController::class, 'destroy'])->name('entites.destroy');


    /*
    |--------------------------------------------------------------------------
    | DEMANDES
    |--------------------------------------------------------------------------
    */
    Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/create', [DemandeController::class, 'create'])->name('demandes.create');
    Route::post('/demandes', [DemandeController::class, 'store'])->name('demandes.store');

    Route::get('/demandes/en-attente-daf', [DemandeController::class, 'enAttenteDaf'])->name('demandes.enAttenteDaf');
    Route::get('/demandes/en-attente-directeur', [DemandeController::class, 'enAttenteDirecteur'])->name('demandes.enAttenteDirecteur');
    Route::get('/demandes/en-attente-controleur', [DemandeController::class, 'enAttenteControl'])->name('demandes.enAttenteControl');

    Route::get('/demandes/valider', [DemandeController::class, 'valider'])->name('demandes.valider');

    // Routes spécifiques AVANT la route dynamique
    Route::get('/demandes/{id}/imprimer', [DemandeController::class, 'imprimer'])->name('demandes.imprimer');
    Route::get('/demandes/valider/{id}', [DemandeController::class, 'showValider'])->name('demandes.showValider');
    Route::get('/demandes/en-attente-directeur/{id}', [DemandeController::class, 'showEnAttenteDirecteur'])->name('demandes.showEnAttenteDirecteur');
    Route::get('/demandes/en-attente-daf/{id}', [DemandeController::class, 'showEnAttenteDaf'])->name('demandes.showEnAttenteDaf');
    Route::get('/demandes/en-attente-controleur/{id}', [DemandeController::class, 'showEnAttenteControl'])->name('demandes.show_enattente');

    Route::post('/demandes/valider-directeur/{id}', [DemandeController::class, 'validerDirecteur'])->name('demandes.validerDirecteur');
    Route::post('/demandes/refuser-directeur/{id}', [DemandeController::class, 'refuserDirecteur'])->name('demandes.refuserDirecteur');
    Route::post('/demandes/valider-daf/{id}', [DemandeController::class, 'validerDaf'])->name('demandes.validerDaf');
    Route::post('/demandes/refuser-daf/{id}', [DemandeController::class, 'refuserDaf'])->name('demandes.refuserDaf');
    Route::post('/demandes/{id}/valider-controleur', [DemandeController::class, 'validerControleur'])->name('demandes.validerControleur');
    Route::post('/demandes/{id}/refuser-controleur', [DemandeController::class, 'refuserControleur'])->name('demandes.refuserControleur');

    // Route dynamique toujours EN DERNIER
    Route::get('/demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
    Route::post('/demandes/{demande}/annuler', [DemandeController::class, 'annuler'])
         ->name('demandes.annuler');


    /*
    |--------------------------------------------------------------------------
    | PAIEMENTS
    |--------------------------------------------------------------------------
    */

    Route::get('/faire-paiement', [PaiementController::class, 'index'])->name('paiements.index');
    Route::post('/faire-paiement/search', [PaiementController::class, 'search'])->name('faire-paiement.search');

    Route::get('/paiements/valides', [PaiementController::class, 'valides'])->name('paiements.valides');
    Route::get('/paiements/partiellement', [PaiementController::class, 'partiellement'])->name('paiements.partiellement');
    Route::get('/paiements/emis', [PaiementController::class, 'emis'])->name('paiements.emis');
    Route::get('/paiements/encours', [PaiementController::class, 'encours'])->name('paiements.encours');

    Route::post('/paiements/{paiement}/payer', [PaiementController::class, 'payer'])->name('paiements.payer_unique');

    Route::get('/paiements/dg-valider/{id}', [PaiementController::class, 'dgValider'])->name('paiements.dg_valider');
    Route::post('/paiements/dg-valider/{id}', [PaiementController::class, 'validerDG'])->name('paiements.validerDG');
    Route::post('/paiements/dg-refuser/{id}', [PaiementController::class, 'refuserDG'])->name('paiements.refuserDG');

    Route::get('/paiements/{id}/show', [PaiementController::class, 'show'])->name('paiements.show');

    // Route dynamique toujours en dernier
    Route::get('/paiements/{paiement}', [PaiementController::class, 'shown'])->name('paiements.shown');

});
// Routes de gestion des permissions (protégées par auth et vérification de permission dans le controller)
Route::middleware(['auth'])->group(function () {

    // Liste des rôles + leurs permissions
    Route::get('/permissions', [PermissionController::class, 'index'])
        ->name('permissions.index');

    // AJAX : récupérer les permissions d’un rôle
    Route::get('/permissions/role/{role}/data', [PermissionController::class, 'getRolePermissions'])
        ->name('permissions.role.data');

    // Page d’édition des permissions
    Route::get('/permissions/role/{role}', [PermissionController::class, 'edit'])
        ->name('permissions.edit');

    // Mise à jour des permissions
    Route::put('/permissions', [PermissionController::class, 'update'])
        ->name('permissions.update');

    // Enregistrer les permissions
    Route::put('/permissions/save', [PermissionController::class, 'saveRolePermissions'])
        ->name('permissions.save');

});