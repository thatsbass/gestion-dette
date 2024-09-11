<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'surnom', 'adresse', 'telephone', 'user_id'
    ];

    // Relation avec User (Un client peut avoir un utilisateur)
    function user() {
        return $this->belongsTo(User::class);
    }
}