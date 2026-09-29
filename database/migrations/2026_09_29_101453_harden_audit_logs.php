<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $businessTables = ['entres', 'sorties', 'users'];

    public function up(): void
    {
        $driver = DB::getDriverName();

        if (! in_array($driver, ['pgsql', 'mysql', 'mariadb'], true)) {
            throw new RuntimeException("SGBD non pris en charge : {$driver}");
        }

        Schema::table('audit_logs', function (Blueprint $t) {
            $t->char('prev_hash', 64)->nullable()->after('ip_address');
            $t->char('hash', 64)->nullable()->index()->after('prev_hash');
        });

        $driver === 'pgsql' ? $this->upPostgres() : $this->upMysql();
    }

    private function upMysql(): void
    {
        DB::unprepared("CREATE TRIGGER audit_logs_no_update BEFORE UPDATE ON audit_logs
            FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'audit_logs est en lecture seule'");
        DB::unprepared("CREATE TRIGGER audit_logs_no_delete BEFORE DELETE ON audit_logs
            FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'audit_logs est en lecture seule'");

        foreach ($this->businessTables as $table) {
            DB::unprepared("CREATE TRIGGER {$table}_no_delete BEFORE DELETE ON {$table}
                FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Suppression réelle interdite'");
        }
    }

    private function upPostgres(): void
    {
        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE FUNCTION block_write() RETURNS trigger AS $$
            BEGIN
                RAISE EXCEPTION 'Opération interdite sur la table %', TG_TABLE_NAME;
            END;
            $$ LANGUAGE plpgsql;
        SQL);

        DB::unprepared('CREATE TRIGGER audit_logs_no_update BEFORE UPDATE ON audit_logs
            FOR EACH ROW EXECUTE FUNCTION block_write()');
        DB::unprepared('CREATE TRIGGER audit_logs_no_delete BEFORE DELETE ON audit_logs
            FOR EACH ROW EXECUTE FUNCTION block_write()');

        foreach ($this->businessTables as $table) {
            DB::unprepared("CREATE TRIGGER {$table}_no_delete BEFORE DELETE ON {$table}
                FOR EACH ROW EXECUTE FUNCTION block_write()");
        }

        // TRUNCATE ne déclenche pas les triggers "FOR EACH ROW" : il faut un trigger dédié
        foreach (['audit_logs', ...$this->businessTables] as $table) {
            DB::unprepared("CREATE TRIGGER {$table}_no_truncate BEFORE TRUNCATE ON {$table}
                FOR EACH STATEMENT EXECUTE FUNCTION block_write()");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            foreach (['audit_logs', ...$this->businessTables] as $table) {
                DB::unprepared("DROP TRIGGER IF EXISTS {$table}_no_truncate ON {$table}");
            }
            foreach (['audit_logs_no_update' => 'audit_logs', 'audit_logs_no_delete' => 'audit_logs'] as $trg => $table) {
                DB::unprepared("DROP TRIGGER IF EXISTS {$trg} ON {$table}");
            }
            foreach ($this->businessTables as $table) {
                DB::unprepared("DROP TRIGGER IF EXISTS {$table}_no_delete ON {$table}");
            }
            DB::unprepared('DROP FUNCTION IF EXISTS block_write()');
        } else {
            foreach (['audit_logs_no_update', 'audit_logs_no_delete', 'entres_no_delete', 'sorties_no_delete', 'users_no_delete'] as $trg) {
                DB::unprepared("DROP TRIGGER IF EXISTS {$trg}");
            }
        }

        Schema::table('audit_logs', fn (Blueprint $t) => $t->dropColumn(['prev_hash', 'hash']));
    }
};