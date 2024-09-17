<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = ["client_id", "montant", "etat"];

    public function articles()
    {
        return $this->belongsToMany(Article::class, "article_demande")
            ->withPivot("quantity")
            ->withTimestamps();
    }
}
