<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuditLog extends Model
{
    const UPDATED_AT = null;
    protected $guarded = [];
    protected $casts = ['old_values' => 'array', 'new_values' => 'array'];

    protected static function booted()
    {
        static::updating(fn () => false);
        static::deleting(fn () => false);
    }

    public static function record(string $action, $model = null, array $old = [], array $new = []): void
    {
        $user   = auth()->user();
        $hidden = array_flip(['password', 'remember_token']);

        $old = array_diff_key($old, $hidden);
        $new = array_diff_key($new, $hidden);

        DB::transaction(function () use ($action, $model, $user, $old, $new) {
            $last = self::lockForUpdate()->orderByDesc('id')->first();
            $prev = $last->hash ?? str_repeat('0', 64);

            $data = [
                'user_id'    => $user?->id,
                'user_name'  => $user ? trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) : 'Système',
                'user_role'  => $user?->role,
                'action'     => $action,
                'model_type' => $model ? class_basename($model) : null,
                'model_uuid' => $model->uuid ?? null,
                'plaque'     => $model->plaque ?? null,
                'old_values' => $old ?: null,
                'new_values' => $new ?: null,
                'ip_address' => request()?->ip(),
                'created_at' => now()->format('Y-m-d H:i:s'),
                'prev_hash'  => $prev,
            ];
            $data['hash'] = self::computeHash($data, $prev);

            self::create($data);
        });
    }

    public static function computeHash(array $d, string $prev): string
    {
        $fields = ['user_id', 'user_name', 'user_role', 'action', 'model_type',
                   'model_uuid', 'plaque', 'ip_address', 'created_at'];

        $old = is_array($d['old_values'] ?? null) ? json_encode($d['old_values']) : (string) ($d['old_values'] ?? '');
        $new = is_array($d['new_values'] ?? null) ? json_encode($d['new_values']) : (string) ($d['new_values'] ?? '');

        $text = $prev . '|' . implode('|', array_map(fn ($f) => (string) ($d[$f] ?? ''), $fields)) . '|' . $old . '|' . $new;

        return hash_hmac('sha256', $text, config('app.key'));
    }
}