<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Don extends Model
{
    use HasFactory;

    protected $table = 'dons';

    protected $fillable = [
        'user_id',
        'annonce_id',
        'etat',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function annonce(): BelongsTo
    {
        return $this->belongsTo(Annonce::class);
    }
}
