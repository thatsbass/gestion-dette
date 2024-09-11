<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['libelle', 'prix', 'quantite'];

    public function dettes()
    {
        return $this->belongsToMany(Dette::class, 'article_dette')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}
