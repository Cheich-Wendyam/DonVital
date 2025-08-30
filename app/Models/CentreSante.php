<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentreSante extends Model
{
    use HasFactory;


    protected $fillable = ['nom', 'localisation', 'image', 'description', 'latitude', 'longitude'];


public function campagnes()
{
    return $this->hasMany(Campagne::class, 'centre_sante_id');
}




}
