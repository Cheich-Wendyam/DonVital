<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationRecord extends Model
{
    protected $fillable = [
        'user_id',
        'centre_sante_id',
        'donation_date',
        'volume_ml',
        'blood_type',
        'medical_notes',
        'certificate_path'
    ];

    protected $casts = [
        'donation_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function centre(): BelongsTo
    {
        return $this->belongsTo(CentreSante::class, 'centre_sante_id');
    }
}
