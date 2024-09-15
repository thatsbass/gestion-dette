<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use Notifiable, HasFactory;

    protected $fillable = ["surnom", "adresse", "telephone", "user_id"];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
