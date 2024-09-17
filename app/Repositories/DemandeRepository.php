<?php
namespace App\Repositories;

use App\Models\Demande;
use App\Repositories\Interfaces\DemandeRepositoryInterface;

class DemandeRepository implements DemandeRepositoryInterface
{
    public function create(array $data): Demande
    {
        return Demande::create($data);
    }

    public function getDemandesByClient($clientId, $etat = null)
    {
        $query = Demande::where('client_id', $clientId);

        if ($etat) {
            $query->where('etat', $etat);
        }

        return $query->get();
    }

    public function getAllDemandes($etat = null)
    {
        $query = Demande::query();

        if ($etat) {
            $query->where('etat', $etat);
        }

        return $query->get();
    }

    public function findById($id): ?Demande
    {
        return Demande::find($id);
    }
}
