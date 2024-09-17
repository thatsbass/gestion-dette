<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dette extends Model
{
  

    protected $fillable = ["client_id", "montant", "statut", "limit_at"];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'archived_at' => 'datetime',
    ];
    protected $dates = ['limit_at'];

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
        return $this->belongsToMany(Article::class, "article_dette")
            ->withPivot("quantity", "price")
            ->withTimestamps();
    }

    public function archivedDette()
    {
        return $this->hasOne(ArchiveDette::class, "dette_id");
    }
}
