<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ["libelle", "prix", "quantite"];

    public function dettes()
    {
        return $this->belongsToMany(Dette::class, "article_dette")
            ->withPivot("quantity", "price")
            ->withTimestamps();
    }

    public function demandes()
    {
        return $this->belongsToMany(Demande::class, "demande_article")
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
