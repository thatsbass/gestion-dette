<?php

namespace App\Services\Archive;

use App\Models\Dette;
use MongoDB\Client as MongoClient;
use MongoDB\BSON\UTCDateTime;

class MongoDBArchiveService implements ArchiveServiceInterface
{
    protected $client;
    protected $database;

    public function __construct($dsn, $db)
    {
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
            "dette_id" => $dette->id,
            "client_id" => $dette->client_id,
            "montant" => $dette->montant,
            "articles" => $dette->articles->toArray(),
            "paiements" => $dette->paiements->toArray(),
            "archived_at" => new UTCDateTime(now()->timestamp * 1000),
        ]);
    }

    public function getAll()
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        return $collection->find()->toArray();
    }

    public function getByDate($date)
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        return $collection
            ->find([
                "archived_at" => new UTCDateTime(
                    (new \DateTime($date))->getTimestamp() * 1000
                ),
            ])
            ->toArray();
    }

    public function getByClient($clientId)
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        return $collection->find(["client_id" => $clientId])->toArray();
    }

    public function getById($id)
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        return $collection->findOne(["_id" => new \MongoDB\BSON\ObjectId($id)]);
    }

    public function deleteById($id)
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        $collection->deleteOne(["_id" => new \MongoDB\BSON\ObjectId($id)]);
    }
}
