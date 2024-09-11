<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = ['montant', 'date', 'dette_id', 'client_id'];

    public function dette()
    {
        return $this->belongsTo(Dette::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
