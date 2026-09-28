<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['entres', 'sorties'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->uuid('uuid')->nullable()->unique()->after('id');
            });

            // Remplir les anciennes lignes
            DB::table($table)->whereNull('uuid')->orderBy('id')->each(function ($row) use ($table) {
                DB::table($table)->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
            });
        }
    }

    public function down(): void
    {
        foreach (['entres', 'sorties'] as $table) {
            Schema::table($table, fn (Blueprint $t) => $t->dropColumn('uuid'));
        }
    }
};