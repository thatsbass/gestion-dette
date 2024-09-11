<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Observers\UserObserver;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nom', 'prenom', 'login', 'password', 'photo', 'photo_status', 'role_id', 'is_active'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Relation avec le modèle Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Si cet utilisateur est associé à un client
    public function client()
    {
        return $this->hasOne(Client::class);
    }


    protected static function booted()
    {
        static::observe(UserObserver::class);
    }

   
}
