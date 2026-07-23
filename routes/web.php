<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntresController;
use App\Http\Controllers\SortiesController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TarifController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
  Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs');
    Route::get('/utilisateurs.create', [UserController::class, 'create'])->name('utilisateurs.create');
    Route::post('/utilisateurs', [UserController::class, 'store'])->name('utilisateurs.store');
    Route::get('/utilisateurs/{id}/edit', [UserController::class, 'edit'])->name('utilisateurs.edit');
    Route::put('/utilisateurs/{id}', [UserController::class, 'update'])->name('utilisateurs.update');
    Route::delete('/utilisateurs/{id}', [UserController::class, 'destroy'])->name('utilisateurs.destroy');

    // Tarif update sans vue dédiée : on reste sur utilisateurs
    Route::get('/tarifs', [TarifController::class, 'index'])->name('tarifs');
    Route::put('/tarifs', [TarifController::class, 'update'])->name('tarifs.update');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');


});
Route::middleware(['auth', ])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [SortiesController::class, 'statistiques'])->name('dashboard');
    
});

Route::get('/check-plaque', function (Request $request) {
    $exists = \App\Models\Entres::where('plaque', $request->plaque)
                ->whereNull('sortie_at') // Si pas encore sorti
                ->exists();
    return response()->json(['exists' => $exists]);
});

Route::get('/sorties/ticket/{id}', [SortiesController::class, 'ticket'])->name('sorti-ticket');
Route::get('/sorties/ticket/download/{id}', [SortiesController::class, 'downloadTicket'])->name('ticket.download');


Route::get('/vehicule/info', [App\Http\Controllers\EntresController::class, 'getVehiculeInfo'])->name('vehicule.info');

Route::get('/export/jour', [SortiesController::class, 'exportJour'])->name('export.jour');
Route::get('/export/semaine', [SortiesController::class, 'exportSemaine'])->name('export.semaine');
Route::get('/export/mois', [SortiesController::class, 'exportMois'])->name('export.mois');
Route::get('/export/annee', [SortiesController::class, 'exportAnnee'])->name('export.annee');


// Tableau de bord (statistiques)
Route::post('/tarif-auto', [App\Http\Controllers\SortieController::class, 'getMontant'])->name('get.montant');

Route::get('/statsagent', [SortiesController::class, 'statsAgents'])->name('statsagent');

   

// Activités récentes (accessible sans auth si besoin)
Route::middleware('auth')->group(function () {
Route::get('/recent', [ActivityController::class, 'activites'])->name('recent');

});
// Entrées véhicules (auth requis);

Route::middleware('auth')->group(function () {
Route::get('/entres', [EntresController::class, 'create'])->name('entres.create');
Route::post('/entres', [EntresController::class, 'store'])->name('entres.store');
Route::get('/entres/{id}/ticket', [EntresController::class, 'ticket'])->name('entres.ticket');
Route::get('/entres/ticket-html/{id}', [EntresController::class, 'ticketHtml'])->name('entres.ticket.html');
Route::get('/entres/{id}', [ActivityController::class, 'showEntre'])->name('entres.show');
Route::get('/entres/{id}/edit', [EntresController::class, 'edit'])->name('entres.edit');
Route::put('/entres/{id}', [EntresController::class, 'update'])->name('entres.update');
 Route::delete('/entres/{id}', [EntresController::class, 'destroy'])->name('entres.destroy');
});

// Sorties véhicules (auth requis)
Route::middleware('auth')->group(function () {
    Route::get('/sorties', [SortiesController::class, 'create'])->name('sorties.create');
    Route::post('/sorties', [SortiesController::class, 'store'])->name('sorties.store');
    Route::get('/ticket/sortie/{id}', [SortiesController::class, 'downloadTicket'])->name('ticket-sortie');
    Route::get('sorties/ticket-html/{id}', [SortiesController::class, 'ticketHtml'])->name('sorties.ticket.html');
    Route::get('/sorties/{id}',[ActivityController::class,'showSortie'])->name('sorties.show');
    Route::get('/sorties/{id}/edit', [EntresController::class, 'edit'])->name('sorties.edit');
    Route::put('/sorties/{id}', [EntresController::class, 'update'])->name('sorties.update');
    Route::delete('/sorties/{id}', [EntresController::class, 'destroy'])->name('sorties.destroy');



});

// Profil utilisateur (auth requis)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (register, login, etc.)
require __DIR__.'/auth.php';
