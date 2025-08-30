<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campagne extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'lieu',
        'date_debut',
        'date_fin',
        'groupes_cibles',
        'centre_sante_id',
        'image_url',
        'is_active'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'is_active' => 'boolean',
        'groupes_cibles' => 'array'
    ];

    public function centreSante(): BelongsTo
    {
        return $this->belongsTo(CentreSante::class, 'centre_sante_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedDatesAttribute()
    {
        return $this->date_debut->format('d/m/Y H:i') . ' - ' . $this->date_fin->format('d/m/Y H:i');
    }
    public function getImageUrlAttribute()
{
    return $this->attributes['image_url']
        ? asset('storage/' . $this->attributes['image_url'])
        : null;
}
    public function participants()
    {
        return $this->hasMany(CampagneParticipation::class);
    }


}
