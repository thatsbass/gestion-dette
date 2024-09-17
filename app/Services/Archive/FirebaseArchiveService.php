<?php

namespace App\Services\Archive;

use App\Models\ArchiveDette;
use App\Models\Dette;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseArchiveService implements ArchiveServiceInterface
{
    protected $database;

    public function __construct($credential, $db_url)
    {
        $factory = (new Factory())
            ->withServiceAccount($credential)
            ->withDatabaseUri($db_url);
        $this->database = $factory->createDatabase();
    }

    public function archiveDette(Dette $dette)
    {
        Log::info($dette);
        $this->database->getReference("archive/dettes")->push([
            "dette_id" => $dette->id,
            "client_id" => $dette->client_id,
            "montant" => $dette->montant,
            "articles" => $dette->articles->toArray(),
            "paiements" => $dette->paiements->toArray(),
            "limit_at" => $dette->limit_at,
            "created_at" => $dette->created_at,
            "updated_at" => $dette->updated_at,
            "archived_at" => now(),
        ]);
    }

    public function getAll()
    {
        return $this->database->getReference("archive/dettes")->getValue();
    }

    public function getByDate($date)
    {
        $reference = $this->database->getReference("archive/dettes");
        $snapshot = $reference
            ->orderByChild("archived_at")
            ->equalTo($date)
            ->getSnapshot();
        return $snapshot->getValue() ?: [];
    }

    public function getByClient($clientId)
    {
        $reference = $this->database->getReference("archive/dettes");
        $snapshot = $reference
            ->orderByChild("client_id")
            ->equalTo($clientId)
            ->getSnapshot();
        return $snapshot->getValue() ?: [];
    }

    public function getById($detteId)
    {
        $reference = $this->database
            ->getReference("archive/dettes/")
            ->getValue();

        if ($reference) {
            
            $filtered = array_filter($reference, function ($debt) use (
                $detteId
            ) {
                return $debt["dette_id"] == $detteId;
            });

            return !empty($filtered) ? array_values($filtered)[0] : null;
        }

        return null; 
    }

    public function deleteById($detteId)
    {
        $reference = $this->database
            ->getReference("archive/dettes")
            ->getValue();

        $archiveToDelete = array_filter($reference, function ($archive) use (
            $detteId
        ) {
            return $archive["dette_id"] == $detteId;
        });

        if (!empty($archiveToDelete)) {
            $archiveKey = array_key_first($archiveToDelete);
            $reference = $this->database->getReference(
                "archive/dettes/{$archiveKey}"
            );
            $reference->remove();
        }
    }

}
