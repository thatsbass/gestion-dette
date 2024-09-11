<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;
    use HasFactory, SoftDeletes;

    protected $fillable = ['libelle', 'prix', 'quantite'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at',];


    public function dettes()
    {
        return $this->belongsToMany(Dette::class)->withPivot(['quantity', 'price']);
    }
}
