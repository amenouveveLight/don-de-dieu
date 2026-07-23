<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Tarif;
use App\Models\Entres;
use App\Models\Sorties;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\SyncController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Ping léger pour détecter une vraie connexion au serveur
Route::get('/ping', function () {
    return response()->json(['status' => 'ok'], 200);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/tarifs', function () {
        return Tarif::all(['type', 'tarif']);
    });

    Route::get('/presents', function () {
        return Entres::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('sorties')
                  ->whereColumn('sorties.plaque', 'entres.plaque')
                  ->whereColumn('sorties.created_at', '>', 'entres.created_at');
        })->get(['plaque', 'type', 'name', 'phone', 'created_at']);
    });

    Route::post('/entres', [SyncController::class, 'storeEntree']);
    Route::post('/sorties', [SyncController::class, 'storeSortie']);

});