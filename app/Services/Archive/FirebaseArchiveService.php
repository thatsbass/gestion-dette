<?php

namespace App\Services\Archive;

use App\Models\Dette;
use Kreait\Firebase\Factory;
use Log;

class FirebaseArchiveService implements ArchiveServiceInterface
{
    protected $database;

    public function __construct($credential, $db_url)
    {
        Log::info("DB_URL : " . $db_url);

        $factory = (new Factory())
            ->withServiceAccount($credential)
            ->withDatabaseUri($db_url);
        $this->database = $factory->createDatabase();
    }

    public function archiveDette(Dette $dette): void
    {
        $dette->load("articles", "paiements");

        $this->database->getReference("archive/dettes")->push([
            "client_id" => $dette->client_id,
            "montant" => $dette->montant,
            "articles" => $dette->articles->toArray(),
            "paiements" => $dette->paiements->toArray(),
            "archived_at" => now(),
        ]);
    }
}
