<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BloodRequestNotification;

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
    ];

     // Relation avec User
     public function user(): BelongsTo
     {
         return $this->belongsTo(User::class);
     }
      // Méthode pour envoyer des notifications aux utilisateurs correspondants
    public static function boot()
    {
        parent::boot();

        static::created(function ($annonce) {
            // Rechercher les utilisateurs avec le groupe sanguin correspondant
            $users = User::where('blood_group', $annonce->TypeSang)->get();

            // Envoyer les notifications
            Notification::send($users, new BloodRequestNotification($annonce));
        });
    }
 }
