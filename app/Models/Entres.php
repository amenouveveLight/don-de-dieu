<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entres extends Model
{
    use HasFactory;

    // Utiliser $guarded vide est préférable pour la synchronisation offline.
    // Cela permet au serveur d'accepter TOUS les champs envoyés par le téléphone,
    // y compris le 'created_at' original et le 'user_id'.
    protected $guarded = [];

    /**
     * Relation avec la sortie
     */
    public function sortie()
    {
        // Si tu as une colonne 'entree_id' dans ta table 'sorties' :
        return $this->hasOne(Sorties::class, 'entree_id');
        
        /* 
           NOTE : Si tu n'as PAS de colonne 'entree_id', utilise plutôt ceci :
           return $this->hasOne(Sorties::class, 'plaque', 'plaque')
                       ->whereColumn('created_at', '>', 'entres.created_at');
        */
    }

    /**
     * Relation avec l'agent
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}