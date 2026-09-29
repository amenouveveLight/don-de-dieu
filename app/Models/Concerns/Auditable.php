<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(fn ($m) => AuditLog::record('created', $m, [], $m->getAttributes()));

        static::updated(function ($m) {
            $new = $m->getChanges();
            unset($new['updated_at']);
            if (!$new) return;
            AuditLog::record('updated', $m, array_intersect_key($m->getOriginal(), $new), $new);
        });

        static::deleted(fn ($m) => AuditLog::record('deleted', $m, $m->getOriginal(), []));
    }
}