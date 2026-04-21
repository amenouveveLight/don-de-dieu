<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('sorties', function (Blueprint $table) {
            $table->id();

            $table->string('plaque');                     // Plaque d'immatriculation
            $table->string('type');                       // Type de véhicule (car, truck, etc.)
            $table->integer('montant');                   // Tarif fixe par type
            $table->string('paiement');                   // Mode de paiement (cash, card, app)
            $table->boolean('paiement_ok')->default(false); // Paiement effectué ou non

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Opérateur (user)

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_sorties');
    }
};
