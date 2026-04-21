<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('tarifs', function (Blueprint $table) {
        $table->id();
        $table->string('type')->unique(); // ex: motorcycle, car, etc.
        $table->string('label');          // ex: Moto, Voiture
        $table->integer('tarif');         // tarif en FCFA (par exemple)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};
