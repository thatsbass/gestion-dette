<?php
namespace App\Services\Interfaces;

use App\Models\Demande;
use Illuminate\Support\Collection;

interface DemandeServiceInterface
{
    public function createDemande(array $data): Demande;

    public function getDemandesByClient(int $clientId, ?string $etat = null): Collection;
    public function findDemandeById(int $id): Demande;
    public function getNotifications(): void;
    public function getAllDemandes($etat = null);
    // public function sendRelance(Demande $demande): void;
}
