<?php

namespace App\Services\Archive;

use App\Models\Dette;
use MongoDB\Client as MongoClient;
use Log;

class MongoDBArchiveService implements ArchiveServiceInterface
{
    protected $client;
    protected $database;
    public function __construct($dsn, $db)
    {
        Log::info("DSN : " . $dsn . "</br>" . "DB : " . $db);

        $this->client = new MongoClient($dsn);
        $this->database = $db;
    }

    public function archiveDette(Dette $dette): void
    {
        // Charger les relations de la dette
        $dette->load("articles", "paiements");

        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        $collection->insertOne([
            "client_id" => $dette->client_id,
            "montant" => $dette->montant,
            "articles" => $dette->articles->toArray(),
            "paiements" => $dette->paiements->toArray(),
            "archived_at" => now(),
        ]);
    }
}
