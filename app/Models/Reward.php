<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Import manquant
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Reward extends Model
{
    protected $fillable = [
        'name',
        'description',
        'cost',
        'image_url',
        'is_active',
        'required_level'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('claimed_at')
                    ->withTimestamps();
    }
}
