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
        Schema::table('sorties', function (Blueprint $table) {
            // Supprime la contrainte de clé étrangère
            $table->dropForeign(['user_id']);

            // Supprime la colonne user_id
            $table->dropColumn('user_id');

            // Ajoute les nouveaux champs
            $table->string('owner_name')->after('plaque');
            $table->string('owner_phone')->after('owner_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('sorties', function (Blueprint $table) {
            // Supprime les champs ajoutés
            $table->dropColumn(['owner_name', 'owner_phone']);

            // Recrée la colonne user_id
            $table->unsignedBigInteger('user_id')->nullable();

            // Recrée la contrainte
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
