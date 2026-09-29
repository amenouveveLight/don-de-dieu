<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VerifyAuditChain extends Command
{
    protected $signature = 'audit:verify';
    protected $description = 'Vérifie que le journal audit_logs n\'a pas été altéré';

    public function handle(): int
    {
        $prev = str_repeat('0', 64);
        $count = 0;
        $started = false; // devient vrai à la première ligne hachée

        foreach (AuditLog::orderBy('id')->cursor() as $log) {
            if ($log->hash === null) {
                if ($started) {
                    return $this->fail($log, 'ligne sans signature');
                }
                continue; // anciennes lignes, antérieures au hachage
            }

            $started = true;

            $raw = [
                'user_id' => $log->user_id, 'user_name' => $log->user_name, 'user_role' => $log->user_role,
                'action' => $log->action, 'model_type' => $log->model_type, 'model_uuid' => $log->model_uuid,
                'plaque' => $log->plaque, 'ip_address' => $log->ip_address,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                'old_values' => $log->old_values, 'new_values' => $log->new_values,
            ];

            $expected = AuditLog::computeHash($raw, $log->prev_hash);

            if ($count > 0 && $log->prev_hash !== $prev) {
                return $this->fail($log, 'chaînage rompu');
            }

            if (! hash_equals($expected, (string) $log->hash)) {
                return $this->fail($log, 'signature invalide');
            }

            $prev = $log->hash;
            $count++;
        }

        $this->info("Journal intact : {$count} lignes vérifiées. Dernière signature : {$prev}");
        return self::SUCCESS;
    }

    private function fail(AuditLog $log, string $reason): int
    {
        $this->error("ALERTE : {$reason} à la ligne #{$log->id}");
        Log::critical("Audit altéré ({$reason}) à la ligne #{$log->id}");
        return self::FAILURE;
    }
}