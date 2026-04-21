<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Tarif;
use App\Models\Entres;
use App\Models\Sorties;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. Récupérer l'utilisateur (Standard Laravel)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 2. Récupérer les tarifs pour le téléphone (Calcul hors-ligne)
Route::get('/tarifs', function () {
    return Tarif::all(['type', 'tarif']);
});

// 3. Récupérer les véhicules actuellement présents 
// Pour permettre à l'agent de faire sortir une voiture même s'il vient de se mettre hors-ligne
Route::get('/presents', function () {
    return Entres::whereNotExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('sorties')
              ->whereColumn('sorties.plaque', 'entres.plaque')
              ->whereColumn('sorties.created_at', '>', 'entres.created_at');
    })->get(['plaque', 'type', 'name', 'phone', 'created_at']);
});

// 4. Synchronisation de masse (Le téléphone envoie ses données accumulées)
Route::post('/sync-all', function (Request $request) {
    try {
        DB::transaction(function () use ($request) {
            
            // Synchronisation des Entrées
            if ($request->has('entrees')) {
                foreach ($request->entrees as $e) {
                    Entres::updateOrCreate(
                        ['plaque' => $e['plaque'], 'created_at' => $e['created_at']],
                        [
                            'user_id' => $e['user_id'] ?? 1,
                            'type'    => $e['type'],
                            'name'    => $e['name'] ?? 'Inconnu',
                            'phone'   => $e['phone'] ?? '-',
                        ]
                    );
                }
            }

            // Synchronisation des Sorties
            if ($request->has('sorties')) {
                foreach ($request->sorties as $s) {
                    Sorties::updateOrCreate(
                        ['plaque' => $s['plaque'], 'created_at' => $s['created_at']],
                        [
                            'user_id'     => $s['user_id'] ?? 1,
                            'owner_name'  => $s['owner_name'],
                            'owner_phone' => $s['owner_phone'],
                            'type'        => $s['type'],
                            'montant'     => $s['montant'],
                            'paiement'    => $s['paiement'],
                            'paiement_ok' => 1,
                        ]
                    );
                }
            }
        });

        return response()->json(['status' => 'success', 'message' => 'Synchro réussie']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});

// 5. Enregistrement direct (Online direct)
Route::post('/entres', function (Request $request) {
    return Entres::create($request->all());
});

Route::post('/sorties', function (Request $request) {
    return Sorties::create($request->all());
});