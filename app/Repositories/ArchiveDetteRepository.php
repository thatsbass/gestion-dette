<?php
namespace App\Repositories;

use App\Models\ArchivedDette;
use App\Services\Archive\ArchiveServiceInterface;
use App\Models\Dette;
use App\Models\Paiement;
use App\Models\ArchiveDette;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\UTCDateTime;
use Log;
class ArchiveDetteRepository
{
    protected $archiveService;

    public function __construct(ArchiveServiceInterface $archiveService)
    {
        $this->archiveService = $archiveService;
    }

    public function getAll($clientId = null, $date = null)
    {
        $allDettes = $this->archiveService->getAll();

        $filteredDettes = array_filter($allDettes, function ($dette) use (
            $clientId,
            $date
        ) {
            if ($clientId && $dette["client_id"] != $clientId) {
                return false;
            }
            if ($date && $dette["archived_at"] != $date) {
                return false;
            }
            return true;
        });

        return $filteredDettes;
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
        
        $this->archiveService->deleteById($id);
    }

    public function restoreByDate($date)
    {
        $archivedDettes = $this->archiveService->getByDate($date);

        foreach ($archivedDettes as $dette) {
            $this->restore($dette);
        }
    }

    public function restoreByClient($clientId)
    {
        $archivedDettes = $this->archiveService->getByClient($clientId);
        foreach ($archivedDettes as $dette) {
            $this->restore($dette);
        }
    }

    public function restoreById($id)
    {
        $dette = $this->archiveService->getById($id);
        if ($dette) {
            $this->restore($dette);
        } else {
            throw new \Exception("Archived debt not found.");
        }
    }


    protected function restore(array $dette)
{
    Log::info("Dette formatée en tableau", $dette);

    DB::transaction(function () use ($dette) {
        $formatDate = function ($date) {
            return is_string($date) && strtotime($date) ? $date : null;
        };

        $restoredDette = Dette::create([
            "montant" => $dette["montant"],
            "client_id" => $dette["client_id"],
            "statut" => "paid",
            "limit_at" => $dette["limit_at"],
            "updated_at" => $formatDate($dette["updated_at"]),
            "created_at" => $formatDate($dette["created_at"]),
        ]);

        ArchiveDette::create([
            "dette_id" => $restoredDette->id,
            "client_id" => $dette["client_id"],
            "montant" => $dette["montant"],
            'archived_at' => $dette["archived_at"],
            "restored_at" => now(),
            "cloud_from" => config("archive.default"),
            "created_at" => $formatDate($dette["created_at"]),
            "updated_at" => $formatDate($dette["updated_at"]),
        ]);

       
        if (isset($dette["paiements"]) && is_array($dette["paiements"])) {
            foreach ($dette["paiements"] as $paiement) {
                Paiement::create([
                    "montant" => $paiement["montant"],
                    "dette_id" => $restoredDette->id,
                    "client_id" => $dette["client_id"],
                ]);
            }
        }

        if (isset($dette["articles"]) && is_array($dette["articles"])) {
            foreach ($dette["articles"] as $article) {
                $restoredDette->articles()->attach($article["id"], [
                    "quantity" => $article["pivot"]["quantity"],
                    "price" => $article["pivot"]["price"],
                ]);
            }
        }

        $this->deleteArchivedDette($dette["dette_id"]);
    });
}

}
