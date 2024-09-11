<?php


namespace App\Repositories;

use App\Models\Paiement;
use App\Repositories\Interfaces\PaiementRepositoryInterface;

class PaiementRepository implements PaiementRepositoryInterface
{
    public function create(array $data)
    {
        return Paiement::create($data);
    }

    public function findById(int $id)
    {
        return Paiement::find($id);
    }

    public function update($paiement, array $data)
    {
        $paiement->update($data);
        return $paiement;
    }

    public function delete($paiement)
    {
        $paiement->delete();
    }

    public function getByDette(int $detteId)
    {
        return Paiement::where('dette_id', $detteId)->get();
    }
}
