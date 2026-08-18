<?php

use App\Http\Controllers\CreneauController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RendezVousController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Liste des créneaux disponibles
    Route::get('/creneaux', [CreneauController::class, 'index'])
        ->name('creneaux.index');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Mes rendez-vous
    Route::get('/rendez-vous', [RendezVousController::class, 'index'])
        ->name('rendez-vous.index');

    // Réserver un créneau
    Route::post('/rendez-vous', [RendezVousController::class, 'store'])
        ->name('rendez-vous.store');

    // Annuler un rendez-vous
    Route::delete('/rendez-vous/{rendezVous}', [RendezVousController::class, 'destroy'])
        ->name('rendez-vous.destroy');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/rendez-vous/{rendezVous}/status', [\App\Http\Controllers\Admin\DashboardController::class, 'updateStatus'])->name('rendez-vous.update-status');
    Route::resource('creneaux', \App\Http\Controllers\Admin\CreneauController::class);
});

require __DIR__.'/auth.php';