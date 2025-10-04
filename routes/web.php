<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EntiteController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\PaiementController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/// Page d’accueil
Route::get('/', function () {
    return view('auth.login');
});
// Formulaire mot de passe oublié
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');

// Envoi du mail de réinitialisation
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Formulaire réinitialisation mot de passe
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
    ->name('password.reset');

// Soumettre le nouveau mot de passe
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');
// Auth
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register.form');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Utilisateurs
Route::get('/users', [AuthController::class, 'listregister'])->name('users.list');
Route::get('/users/{id}/edit', [AuthController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [AuthController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [AuthController::class, 'destroy'])->name('users.destroy');



Route::get('/deconnexion', [AuthController::class, 'logout'])->name('logout');
// Dashboard (protégé)
Route::get('/dashboard', [AdminController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');
    //roles
 Route::middleware('auth')->group(function () {
    
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');       // liste des rôles
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create'); // formulaire création
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');      // stockage
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit'); // formulaire édition
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update'); // mise à jour
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy'); // suppression
});

//entite
Route::middleware('auth')->group(function () {
    
    Route::get('/entites', [EntiteController::class, 'indexe'])->name('entites.index');       // liste des entites
    Route::get('/entites/create', [EntiteController::class, 'create'])->name('entites.create'); // formulaire création
    Route::post('/entites', [EntiteController::class, 'store'])->name('entites.store');      // stockage
    Route::get('/entites/{entite}/edit', [EntiteController::class, 'edit'])->name('entites.edit'); // formulaire édition
    Route::put('/entites/{entite}', [EntiteController::class, 'update'])->name('entites.update'); // mise à jour
    Route::delete('/entites/{entite}', [EntiteController::class, 'destroy'])->name('entites.destroy'); // suppression
});




Route::middleware('auth')->group(function () {
    
  // Liste des demandes
Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
// Route pour afficher le formulaire de création de DP
Route::get('/demandes/create', [DemandeController::class, 'create'])->name('demandes.create');
// Route pour enregistrer une nouvelle demande
Route::post('/demandes', [DemandeController::class, 'store'])->name('demandes.store');
// Liste des demandes en attente DAF
Route::get('/demandes/en-attente-daf', [DemandeController::class, 'enAttenteDaf'])->name('demandes.enAttenteDaf');
// Liste des demandes en attente Directeur
Route::get('/demandes/en-attente-directeur', [DemandeController::class, 'enAttenteDirecteur'])->name('demandes.enAttenteDirecteur');
// Route pour afficher les demandes validées par le DG
Route::get('/demandes/valider', [DemandeController::class, 'valider'])->name('demandes.valider');
//impression Dp
Route::get('demandes/{id}/imprimer', [DemandeController::class, 'imprimer']) ->name('demandes.imprimer');

// Pour afficher une demande validée
Route::get('/demandes/valider/{id}', [DemandeController::class, 'showValider'])->name('demandes.showValider');

// Détail d’une demande Directeur
Route::get('/demandes/en-attente-directeur/{id}', [DemandeController::class, 'showEnAttenteDirecteur'])->name('demandes.showEnAttenteDirecteur');

// Actions Directeur
Route::post('/demandes/valider-directeur/{id}', [DemandeController::class, 'validerDirecteur'])->name('demandes.validerDirecteur');
Route::post('/demandes/refuser-directeur/{id}', [DemandeController::class, 'refuserDirecteur'])->name('demandes.refuserDirecteur');


// Détail d'une demande précise
Route::get('/demandes/en-attente-daf/{id}', [DemandeController::class, 'showEnAttenteDaf'])->name('demandes.showEnAttenteDaf');
Route::post('/demandes/valider-daf/{id}', [DemandeController::class, 'validerDaf'])->name('demandes.validerDaf');
Route::post('/demandes/refuser-daf/{id}', [DemandeController::class, 'refuserDaf'])->name('demandes.refuserDaf');

Route::get('/demandes/en-attente-controleur', [DemandeController::class, 'enAttenteControl'])->name('demandes.enAttenteControl');
Route::get('/demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
Route::get('demandes/en-attente-controleur/{id}', [DemandeController::class, 'showEnAttenteControl'])->name('demandes.show_enattente');
// Pour le contrôleur
Route::post('/demandes/{id}/valider-controleur', [DemandeController::class, 'validerControleur'])->name('demandes.validerControleur');
Route::post('/demandes/{id}/refuser-controleur', [DemandeController::class, 'refuserControleur'])->name('demandes.refuserControleur');



//paiements 

Route::get('/faire-paiement', [PaiementController::class, 'index'])->name('paiements.index');
Route::get('/faire-paiement/search', [PaiementController::class, 'index'])->name('faire-paiement.index');
Route::post('/faire-paiement/search', [PaiementController::class, 'search'])->name('faire-paiement.search');


// Paiements validés / effectués
Route::get('/paiements/valides', [PaiementController::class, 'valides'])->name('paiements.valides');
// Paiements partiellement payés
Route::get('/paiements/partiellement', [PaiementController::class, 'partiellement'])->name('paiements.partiellement');
Route::post('/paiements/{demande}', [PaiementController::class, 'store'])->name('paiements.payer');

Route::post('/faire-paiement/{paiementId}/payer', [PaiementController::class, 'payer'])->name('paiements.payer');

Route::get('/paiements/emis', [PaiementController::class, 'emis'])->name('paiements.emis');
Route::get('/paiements/encours', [PaiementController::class, 'encours'])->name('paiements.encours');
Route::post('/paiements/{paiement}/payer', [PaiementController::class, 'payer'])->name('paiements.payer');
// Afficher le paiement à valider par le DG
Route::get('/paiements/dg-valider/{id}', [PaiementController::class, 'dgValider'])->name('paiements.dg_valider');

// Action pour valider le paiement par le DG
Route::post('/paiements/dg-valider/{id}', [PaiementController::class, 'validerDG'])->name('paiements.validerDG');

// Action pour refuser le paiement par le DG
Route::post('/paiements/dg-refuser/{id}', [PaiementController::class, 'refuserDG'])->name('paiements.refuserDG');


 
});
