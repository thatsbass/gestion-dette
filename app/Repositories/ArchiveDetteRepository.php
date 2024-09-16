<?php

namespace App\Repositories;

use App\Services\Archive\ArchiveServiceInterface;
use App\Models\Dette;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;

class ArchiveDetteRepository
{
    protected $archiveService;

    public function __construct(ArchiveServiceInterface $archiveService)
    {
        $this->archiveService = $archiveService;
    }

    public function getAll()
    {
        return $this->archiveService->getAll();
    }

    public function getByClient($clientId)
    {
        return $this->archiveService->getByClient($clientId);
    }

    public function getById($id)
    {
        return $this->archiveService->getById($id);
    }

    public function deleteArchivedDette($id)
    {
        // Supprimer les données archivées dans Firebase ou MongoDB
        $this->archiveService->deleteById($id);
    }

    public function restoreByDate($date)
    {
        // Récupérer toutes les dettes archivées à la date spécifiée
        $archivedDettes = $this->archiveService->getByDate($date);

        // Restaurer chaque dette
        foreach ($archivedDettes as $dette) {
            $this->restore($dette);
        }
    }

    public function restoreByClient($clientId)
    {
        // Récupérer toutes les dettes archivées pour le client spécifié
        $archivedDettes = $this->archiveService->getByClient($clientId);

        // Restaurer chaque dette
        foreach ($archivedDettes as $dette) {
            $this->restore($dette);
        }
    }

    protected function restore(array $dette)
    {
        DB::transaction(function () use ($dette) {
            // Restauration de la dette
            $restoredDette = Dette::create([
                "montant" => $dette["montant"],
                "client_id" => $dette["client_id"],
                "statut" => "pending", // Réinitialiser le statut
                "limit_at" => $dette["limit_at"],
            ]);

            // Restaurer les paiements associés
            foreach ($dette["paiements"] as $paiement) {
                Paiement::create([
                    "montant" => $paiement["montant"],
                    "date" => $paiement["date"],
                    "dette_id" => $restoredDette->id,
                    "client_id" => $dette["client_id"],
                ]);
            }

            // Restaurer les articles associés
            foreach ($dette["articles"] as $article) {
                $restoredDette->articles()->attach($article["id"], [
                    "quantity" => $article["quantity"],
                    "price" => $article["price"],
                ]);
            }

            // Supprimer les données archivées maintenant que la restauration est terminée
            $this->deleteArchivedDette($restoredDette->id);
        });
    }
}
