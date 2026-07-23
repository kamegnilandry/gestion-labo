<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeAnalyseController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrelevementController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\ResultatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// --- Authentification ---
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login']);
});
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Patients (accueil & réception) ---
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::middleware('role:receptionniste')->group(function () {
        Route::get('/patients/creer', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/patients/{patient}/modifier', [PatientController::class, 'edit'])->name('patients.edit');
        Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    });

    // --- Catalogue des examens ---
    Route::get('/examens', [ExamenController::class, 'index'])->name('examens.index');
    Route::middleware('role:biologiste')->group(function () {
        Route::get('/examens/creer', [ExamenController::class, 'create'])->name('examens.create');
        Route::post('/examens', [ExamenController::class, 'store'])->name('examens.store');
        Route::get('/examens/{examen}/modifier', [ExamenController::class, 'edit'])->name('examens.edit');
        Route::put('/examens/{examen}', [ExamenController::class, 'update'])->name('examens.update');
        Route::delete('/examens/{examen}', [ExamenController::class, 'destroy'])->name('examens.destroy');
    });

    // --- Demandes d'analyses ---
    Route::get('/demandes', [DemandeAnalyseController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/{demande}', [DemandeAnalyseController::class, 'show'])->name('demandes.show');
    Route::middleware('role:receptionniste')->group(function () {
        Route::get('/demandes/creer', [DemandeAnalyseController::class, 'create'])->name('demandes.create');
        Route::post('/demandes', [DemandeAnalyseController::class, 'store'])->name('demandes.store');
    });

    // --- Prélèvements ---
    Route::middleware('role:technicien')->group(function () {
        Route::get('/demandes/{demande}/prelevement', [PrelevementController::class, 'create'])->name('prelevements.create');
        Route::post('/demandes/{demande}/prelevement', [PrelevementController::class, 'store'])->name('prelevements.store');
    });

    // --- Résultats ---
    Route::middleware('role:technicien')->group(function () {
        Route::get('/demandes/{demande}/resultats', [ResultatController::class, 'edit'])->name('resultats.edit');
        Route::post('/demandes/{demande}/resultats', [ResultatController::class, 'update'])->name('resultats.update');
    });

    // --- Validation biologiste ---
    Route::post('/demandes/{demande}/valider', [DemandeAnalyseController::class, 'valider'])
        ->name('demandes.valider')->middleware('role:biologiste');
    Route::post('/demandes/{demande}/annuler', [DemandeAnalyseController::class, 'annuler'])
        ->name('demandes.annuler')->middleware('role:receptionniste,biologiste');

    // --- Comptes-rendus ---
    Route::get('/demandes/{demande}/rapport', [RapportController::class, 'show'])->name('rapports.show');

    // --- Gestion des utilisateurs (admin) ---
    Route::middleware('role:admin')->group(function () {
        Route::resource('utilisateurs', UserController::class)->except(['show']);
    });
});
