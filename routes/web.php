<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EntiteController;
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

