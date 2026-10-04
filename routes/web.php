<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\EntresController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SortiesController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Règle générale : tout est protégé par 'auth'. Seules la page d'accueil
| et les routes de connexion (routes/auth.php) sont publiques.
| Les droits fins (propriétaire / rôle) sont vérifiés dans les contrôleurs
| via OwnedRecordPolicy ($this->authorize(...)).
*/

// ---------------------------------------------------------------
// Public
// ---------------------------------------------------------------
Route::get('/', fn () => view('welcome'));

// ---------------------------------------------------------------
// Administrateur uniquement
// ---------------------------------------------------------------
Route::middleware(['auth', 'admin'])->group(function () {

    // Utilisateurs
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs');
    Route::get('/utilisateurs/create', [UserController::class, 'create'])->name('utilisateurs.create');
    Route::post('/utilisateurs', [UserController::class, 'store'])->name('utilisateurs.store');
    Route::get('/utilisateurs/{id}/edit', [UserController::class, 'edit'])->name('utilisateurs.edit');
    Route::put('/utilisateurs/{id}', [UserController::class, 'update'])->name('utilisateurs.update');
    Route::delete('/utilisateurs/{id}', [UserController::class, 'destroy'])->name('utilisateurs.destroy');

    // Tarifs et paramètres
    Route::get('/tarifs', [TarifController::class, 'index'])->name('tarifs');
    Route::put('/tarifs', [TarifController::class, 'update'])->name('tarifs.update');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Journal d'audit complet (connexions, utilisateurs...)
    Route::get('/audit/complet', [AuditController::class, 'complet'])->name('audit.complet');
});

// ---------------------------------------------------------------
// Utilisateurs connectés (admin, gérant, agent)
// Le rôle est re-vérifié dans les contrôleurs quand nécessaire.
// ---------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Tableau de bord et statistiques
    Route::get('/dashboard', [SortiesController::class, 'statistiques'])->name('dashboard');
    Route::get('/statsagent', [SortiesController::class, 'statsAgents'])->name('statsagent'); // admin/gérant (contrôleur)

    // Exports PDF
    Route::get('/export/jour', [SortiesController::class, 'exportJour'])->name('export.jour');
    Route::get('/export/semaine', [SortiesController::class, 'exportSemaine'])->name('export.semaine');
    Route::get('/export/mois', [SortiesController::class, 'exportMois'])->name('export.mois');
    Route::get('/export/annee', [SortiesController::class, 'exportAnnee'])->name('export.annee');

    // Activités récentes
    Route::get('/recent', [App\Http\Controllers\ActivityController::class, 'activites'])->name('recent');

    // Audit (admin + gérant, vérifié dans AuditController)
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/history/{uuid}', [AuditController::class, 'history'])->name('audit.history');

    // Entrées de véhicules
    Route::get('/check-plaque', [EntresController::class, 'checkPlaque'])
        ->middleware('throttle:60,1')->name('entres.check');
    Route::get('/entres', [EntresController::class, 'create'])->name('entres.create');
    Route::post('/entres', [EntresController::class, 'store'])->name('entres.store');
    Route::get('/entres/ticket-ht/{uuid}', [EntresController::class, 'ticketHtml'])->name('entres.ticket.html');
    Route::get('/entres/{uuid}', [EntresController::class, 'show'])->name('entres.show');
    Route::get('/entres/{uuid}/edit', [EntresController::class, 'edit'])->name('entres.edit');
    Route::put('/entres/{uuid}', [EntresController::class, 'update'])->name('entres.update');
    Route::delete('/entres/{uuid}', [EntresController::class, 'destroy'])->name('entres.destroy');

    // Sorties de véhicules
    Route::get('/sorties', [SortiesController::class, 'create'])->name('sorties.create');
    Route::post('/sorties', [SortiesController::class, 'store'])->name('sorties.store');
    Route::get('/sorties/ticket-html/{uuid}', [SortiesController::class, 'ticketHtml'])->name('sorties.ticket.html');
    Route::get('/ticket/sortie/{uuid}', [SortiesController::class, 'downloadTicket'])->name('ticket-sortie');
    Route::get('/sorties/{uuid}', [SortiesController::class, 'show'])->name('sorties.show');
    Route::get('/sorties/{uuid}/edit', [SortiesController::class, 'edit'])->name('sorties.edit');
    Route::put('/sorties/{uuid}', [SortiesController::class, 'update'])->name('sorties.update');
    Route::delete('/sorties/{uuid}', [SortiesController::class, 'destroy'])->name('sorties.destroy');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentification (login, mot de passe oublié...) — pas d'inscription publique
require __DIR__.'/auth.php';