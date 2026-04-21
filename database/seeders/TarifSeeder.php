<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $tarifs = [
        ['type' => 'motorcycle', 'label' => 'Moto', 'tarif' => 100],
        ['type' => 'car',        'label' => 'Voiture', 'tarif' => 500],
        ['type' => 'tricycle',   'label' => 'Tricycle', 'tarif' => 250],
        ['type' => 'nyonyovi',   'label' => 'Nyonyovi', 'tarif' => 250],
        ['type' => 'minibus',    'label' => 'Minibus', 'tarif' => 300],
        ['type' => 'bus',        'label' => 'Bus', 'tarif' => 700],
        ['type' => 'truck',      'label' => 'Camion', 'tarif' => 700],
    ];

    foreach ($tarifs as $tarif) {
        \App\Models\Tarif::updateOrCreate(['type' => $tarif['type']], $tarif);
    }
}

}