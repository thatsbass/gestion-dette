<?php

namespace App\Services\Archive;

use App\Models\ArchiveDette;
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

    public function getById($id)
    {
        $reference = $this->database->getReference("archive/dettes/" . $id);
        return $reference->getValue();
    }

    public function deleteById($id)
    {
        $reference = $this->database->getReference("archive/dettes/" . $id);
        $reference->remove();
    }
}
