<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Annonce extends Model
{
    use HasFactory;
    protected $table = 'annonces';

    protected $fillable = [
        'titre',
        'description',
        'raison',
        'TypeSang',
        'CentreSante',
        'user_id',
        'etat',
    ];

     // Relation avec User
     public function user(): BelongsTo
     {
         return $this->belongsTo(User::class);
     }
      
     // Relation avec Notification
     public function notifications(): HasMany
     {
         return $this->hasMany(Notification::class);
     }

     public function dons()
{
    return $this->hasMany(Don::class);
}

    
 }
