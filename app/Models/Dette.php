<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dette extends Model
{
    protected $fillable = ['montant', 'date', 'client_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_dette')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}

