<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarif;
use Illuminate\Support\Facades\Cache;

class TarifController extends Controller
{
   public function index()
{
    // Exemple : récupérer depuis la base tous les tarifs sous forme clé=>valeur (type => tarif)
    $tarifs = Tarif::pluck('tarif', 'type')->toArray();

    return view('tarifs.index', compact('tarifs'));
}




  // TarifController.php

public function update(Request $request)
{
    $types = config('vehicle_types');

    foreach ($request->tarifs as $type => $tarif) {
        if (!isset($types[$type])) {
            continue; // ignore types inconnus
        }

        \App\Models\Tarif::updateOrCreate(
            ['type' => $type ],
            
               [ 'label' => $types[$type], // prend label dans config
                'tarif' => $tarif,
            ]
        );
    }

    return back()->with('success', 'Tarifs mis à jour avec succès.');
}
}