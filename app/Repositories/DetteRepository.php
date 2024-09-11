<?php

namespace App\Repositories;

use App\Models\Dette;
use App\Repositories\Interfaces\DetteRepositoryInterface;

class DetteRepository implements DetteRepositoryInterface
{
    public function create(array $data)
    {
        return Dette::create($data);
    }

    public function findById(int $id)
    {
        return Dette::find($id);
    }

    public function update($dette, array $data)
    {
        $dette->update($data);
        return $dette;
    }

    public function delete($dette)
    {
        $dette->delete();
    }

    public function getAll()
    {
        return Dette::all();
    }

    public function getByStatus($status)
    {
        $query = Dette::query();
        if ($status === 'Solde') {
            $query->whereRaw('(montant - (SELECT COALESCE(SUM(montant), 0) FROM paiements WHERE dette_id = dettes.id)) = 0');
        } elseif ($status === 'NonSolde') {
            $query->whereRaw('(montant - (SELECT COALESCE(SUM(montant), 0) FROM paiements WHERE dette_id = dettes.id)) != 0');
        }
        return $query->get();
    }
}
