<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_group',
    ];

    // 🔁 Relation Many-to-Many avec User
    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user')
                    ->withTimestamps();
    }

    // 🔁 Une conversation a plusieurs messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // ✅ Dernier message de la conversation (utile pour résumé WhatsApp-like)
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }



}
