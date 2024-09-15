<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveDette extends Model
{
    protected $fillable = [
        "client_id",
        "montant",
        "articles",
        "paiements",
        "archived_at",
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
