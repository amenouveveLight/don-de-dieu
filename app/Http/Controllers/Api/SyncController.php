<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entres;
use App\Models\Sorties;
use App\Models\Tarif;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function storeEntree(Request $request)
    {
        $validated = $request->validate([
            'plaque'     => 'required|string|max:255',
            'type'       => 'required|string',
            'name'       => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'created_at' => 'nullable|date',
        ]);

        $lastEntry = Entres::where('plaque', $validated['plaque'])
                            ->where('type', $validated['type'])
                            ->latest()->first();

        if ($lastEntry) {
            $hasExit = Sorties::where('plaque', $validated['plaque'])
                            ->where('type', $validated['type'])
                            ->where('created_at', '>=', $lastEntry->created_at)
                            ->exists();

            if (!$hasExit) {
                return response()->json([
                    'message' => 'Ce véhicule est déjà présent dans le parking (conflit détecté à la synchronisation).',
                ], 409);
            }
        }

        $entree = Entres::create([
            'plaque'     => $validated['plaque'],
            'type'       => $validated['type'],
            'name'       => $validated['name'] ?? $lastEntry?->name,
            'phone'      => $validated['phone'] ?? $lastEntry?->phone,
            'user_id'    => $request->user()->id,
            'created_at' => $validated['created_at'] ?? now(),
        ]);

        return response()->json(['id' => $entree->id, 'message' => 'Entrée synchronisée.'], 201);
    }

    public function storeSortie(Request $request)
    {
        $validated = $request->validate([
            'plaque'      => 'required|string|exists:entres,plaque',
            'type'        => 'required|string',
            'paiement'    => 'required|in:cash,card,app',
            'paiement_ok' => ['nullable', 'boolean'],
            'created_at'  => 'nullable|date',
        ]);

        $entree = Entres::where('plaque', $validated['plaque'])
            ->where('type', $validated['type'])->latest()->first();

        if (!$entree) {
            return response()->json(['message' => 'Aucune entrée trouvée pour cette plaque.'], 422);
        }

        $derniereSortie = Sorties::where('plaque', $validated['plaque'])
            ->where('type', $validated['type'])->latest()->first();

        if ($derniereSortie && $derniereSortie->created_at >= $entree->created_at) {
            return response()->json(['message' => 'Sortie déjà enregistrée pour cette entrée (conflit).'], 409);
        }

        $joursPasses = $entree->created_at->diffInDays(now()) + 1;
        $tarif = Tarif::where('type', $validated['type'])->first();
        $montantTotal = $joursPasses * ($tarif->tarif ?? 0);

        $sortie = Sorties::create([
            'user_id'     => $request->user()->id,
            'owner_name'  => $entree->name,
            'owner_phone' => $entree->phone,
            'plaque'      => $validated['plaque'],
            'type'        => $validated['type'],
            'montant'     => $montantTotal,
            'paiement'    => $validated['paiement'],
            'paiement_ok' => $request->boolean('paiement_ok'),
            'created_at'  => $validated['created_at'] ?? now(),
        ]);

        return response()->json(['id' => $sortie->id, 'message' => 'Sortie synchronisée.'], 201);
    }
}