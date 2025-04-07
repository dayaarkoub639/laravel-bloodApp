<?php

 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\CompagnesController;
use App\Http\Controllers\CentresController;
use App\Http\Controllers\UrgencesController;
use App\Http\Controllers\HistoriqueController;

use App\Http\Controllers\DonController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('index');

   
// Routes protégées par auth et vérification email
Route::middleware(['auth', 'verified'])->group(function () {

    // Définir une route qui pointe vers MainController@index
Route::get('/dashboard', [MainController::class, 'index'])->name('dashboard');
Route::post('/dashboard', [MainController::class, 'index']);
 // Membres
 Route::prefix('membres')->group(function () {
    Route::get('/', [MembreController::class, 'membres'])->name('membres');
    Route::get('/ajouter', [MembreController::class, 'add'])->name('ajouter-membre');
    Route::post('/ajouter', [MembreController::class, 'store'])->name('membres.store');
    Route::get('/modifier/{id}', [MembreController::class, 'edit'])->name('modifier-membre');
    Route::put('/modifier/{id}', [MembreController::class, 'update'])->name('membres.update');
    Route::get('/fiche/{id}', [MembreController::class, 'show'])->name('fiche-membre');
    Route::delete('/supprimer/{id}', [MembreController::class, 'destroy'])->name('membre.delete');
});
    // Dons
    Route::prefix('dons')->group(function () {
        Route::get('/liste', [DonController::class, 'searchByGroupageView'])->name('dons.list');
        Route::get('/ajouter/{id}', [DonController::class, 'add'])->name('dons.add');
        Route::post('/ajouter', [DonController::class, 'store'])->name('dons.store');
        Route::get('/modifier/{id}', [DonController::class, 'edit'])->name('dons.edit');
        Route::put('/modifier/{id}', [DonController::class, 'update'])->name('dons.update');
        Route::delete('/supprimer/{id}', [DonController::class, 'destroy'])->name('dons.delete');
    });

    // Demandes
    Route::prefix('demandes')->group(function () {
        Route::get('/liste', [DemandeController::class, 'demande'])->name('demandes.liste');
        Route::get('/ajouter', [DemandeController::class, 'add'])->name('ajouter-demande');
        Route::post('/ajouter', [DemandeController::class, 'store'])->name('demandes.store');
        Route::get('/modifier/{id}', [DemandeController::class, 'edit'])->name('demandes.modifier');
        Route::put('/modifier/{id}', [DemandeController::class, 'update'])->name('demandes.update');
        Route::get('/fiche/{id}', [DemandeController::class, 'show'])->name('fiche-demande');
        Route::get('/acceptees/{id}', [DemandeController::class, 'acceptesView'])->name('acceptesView');
        
        Route::delete('/supprimer/{id}', [DemandeController::class, 'destroy'])->name('demande.delete');
    });

    // Compagnes
    Route::prefix('compagnes')->group(function () {
        Route::get('/', [CompagnesController::class, 'index'])->name('compagnes');
        Route::get('/ajouter', [CompagnesController::class, 'add'])->name('ajouter-compagne');
        Route::post('/ajouter', [CompagnesController::class, 'store'])->name('compagnes.store');
        Route::get('/modifier/{id}', [CompagnesController::class, 'edit'])->name('modifier-compagne');
        Route::put('/modifier/{id}', [CompagnesController::class, 'update'])->name('compagnes.update');
        Route::get('/fiche/{id}', [CompagnesController::class, 'show'])->name('fiche-compagne');
        Route::delete('/supprimer/{id}', [CompagnesController::class, 'destroy'])->name('compagne.delete');
    });

    // Centres
    Route::prefix('centres')->group(function () {
        Route::get('/', [CentresController::class, 'index'])->name('centres');
        Route::get('/ajouter', [CentresController::class, 'add'])->name('ajouter-centre');
        Route::post('/ajouter', [CentresController::class, 'store'])->name('centres.store');
        Route::get('/modifier/{id}', [CentresController::class, 'edit'])->name('modify-centre');
        Route::put('/modifier/{id}', [CentresController::class, 'update'])->name('centres.update');
        Route::get('/fiche/{id}', [CentresController::class, 'show'])->name('fiche-centre');
        Route::delete('/supprimer/{id}', [CentresController::class, 'destroy'])->name('centre.delete');
    });

    // Urgences
    Route::get('/urgences', [UrgencesController::class, 'index'])->name('urgences');
    Route::get('/historique', [HistoriqueController::class, 'index'])->name('historique');
    Route::get('/urgences/search', [UrgencesController::class, 'searchByGroupage'])->name('dons.search');

    // Statistiques
    Route::get('/statistiques', [StatistiqueController::class, 'statistique'])->name('statistique');

   
});
require __DIR__.'/auth.php';

 
 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

 