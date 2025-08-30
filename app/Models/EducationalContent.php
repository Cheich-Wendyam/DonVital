<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage; // Ajout de la façade Storage

class EducationalContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'category',
        'difficulty',
        'points',
        'media_path',
        'media_url', // Ajouté pour la compatibilité
        'quiz_data',
        'metadata',
        'is_active'
    ];

    protected $casts = [
        'quiz_data' => 'array',
        'metadata' => 'array'
    ];

    // Ajout des accesseurs pour la compatibilité mobile
    protected $appends = ['media_url_full'];

    public function completions()
    {
         return $this->hasMany(UserContentCompletion::class, 'content_id');
    }

    // Calcul du taux de complétion
    public function getCompletionRateAttribute()
    {
        $totalUsers = User::count();
        if ($totalUsers === 0) return 0;

        return ($this->completions->count() / $totalUsers) * 100;
    }

    // Accesseur pour les URLs média complètes
    public function getMediaUrlFullAttribute()
    {
        // Priorité au fichier uploadé
        if ($this->media_path) {
            return Storage::url($this->media_path);
        }

        // Fallback sur l'URL externe
        return $this->media_url;
    }
}
