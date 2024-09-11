<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['montant', 'date', 'dette_id', 'client_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function dette()
    {
        return $this->belongsTo(Dette::class);
    }


    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    public function scopeBetweenDates(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }


    public function scopeMontantMin(Builder $query, float $montant)
    {
        return $query->where('montant', '>=', $montant);
    }


    public function scopeByClient(Builder $query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }
}