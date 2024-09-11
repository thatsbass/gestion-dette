<?php

namespace App\Models;

use App\Observers\DetteObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Attributes\ObservedBy;

class Dette extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['montant', 'montant_verser', 'montant_restant', 'client_id', 'date'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at',];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withPivot(['quantity', 'price']);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // protected static function booted()
    // {
    //     // Enregistrer l'observer pour le modÃ¨le User
    //     static::observe(DetteObserver::class);
    // }
}