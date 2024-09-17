<?php

namespace App\Repositories\Interfaces;

use App\Models\Demande;

interface DemandeRepositoryInterface
{
    public function create(array $data): Demande;

    public function getDemandesByClient($clientId, $etat = null);

    public function findById($id): ?Demande;
    public function getAllDemandes($etat = null);
    
}
