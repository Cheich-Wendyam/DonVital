<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'titre',
        'message',
        'annonce_id',
        'read',
    ];

     /**
     * Get the user that owns the notification.
     */
    // relation avec user
     public function user()
    {
        return $this->belongsTo(User::class);
    }

     // Relation avec l'annonce
     public function annonce()
     {
         return $this->belongsTo(Annonce::class);
     }
}
