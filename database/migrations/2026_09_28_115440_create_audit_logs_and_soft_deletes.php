<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('user_id')->nullable()->index(); // sans clé étrangère, exprès
            $t->string('user_name');           // copie du nom au moment de l'action
            $t->string('user_role')->nullable();
            $t->string('action');              // created / updated / deleted / login / logout
            $t->string('model_type')->nullable();
            $t->uuid('model_uuid')->nullable()->index();
            $t->string('plaque')->nullable()->index();
            $t->json('old_values')->nullable();
            $t->json('new_values')->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->timestamp('created_at')->useCurrent()->index();
        });

        foreach (['users', 'entres', 'sorties'] as $table) {
            Schema::table($table, fn (Blueprint $t) => $t->softDeletes());
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        foreach (['users', 'entres', 'sorties'] as $table) {
            Schema::table($table, fn (Blueprint $t) => $t->dropSoftDeletes());
        }
    }
};