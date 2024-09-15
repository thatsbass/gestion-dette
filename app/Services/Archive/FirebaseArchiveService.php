<?php

namespace App\Services\Archive;

use App\Models\ArchiveDette;
use Kreait\Firebase\Factory;
use Log;

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

    public function archiveDette(ArchiveDette $dette): void
    {
        $this->database
            ->getReference("archive/dettes")
            ->push($dette->toArray());
    }

    public function getAll()
    {
        return $this->database->getReference("archive/dettes")->getValue();
    }

    public function getByClient($clientId)
    {
        $dettes = $this->getAll();
        return array_filter(
            $dettes,
            fn($dette) => $dette["client_id"] == $clientId
        );
    }

    public function getById($id)
    {
        return $this->database
            ->getReference("archive/dettes/" . $id)
            ->getValue();
    }

    public function restoreByDate($date)
    {
        // Logic to retrieve and delete dettes by date
    }

    public function restoreById($id)
    {
        $this->database->getReference("archive/dettes/" . $id)->remove();
    }

    public function restoreByClient($clientId)
    {
        // Logic to retrieve and delete dettes by client ID
    }
}
