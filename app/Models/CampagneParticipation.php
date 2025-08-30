<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampagneParticipation extends Model
{
    protected $fillable = ['user_id', 'campagne_id', 'status'];

    /**
     * Relation avec l'utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la campagne.
     */
    public function campagne()
    {
        return $this->belongsTo(Campagne::class);
    }
}
