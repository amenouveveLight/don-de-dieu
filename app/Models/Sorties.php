<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sorties extends Model
{
      use HasFactory, SoftDeletes, Auditable;
    // Utiliser $guarded vide pour la synchronisation.
    // Cela permet d'enregistrer le 'created_at' original du téléphone 
    // et le 'user_id' de l'agent sans blocage.
    protected $guarded = [];

    /**
     * Relation avec l'agent qui a effectué la sortie
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    /**
     * (Optionnel) Relation inverse pour retrouver l'entrée correspondante
     */
    public function entree()
    {
        return $this->belongsTo(Entres::class, 'plaque', 'plaque')
                    ->where('created_at', '<', $this->created_at)
                    ->orderBy('created_at', 'desc');
    }

    protected static function booted()
    {
      static::creating(function ($model) {
        // Si le téléphone (synchro offline) envoie déjà un uuid, on le garde
        $model->uuid = $model->uuid ?: (string) Str::uuid();
      });
    }
}