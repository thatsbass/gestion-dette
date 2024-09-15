<?php

namespace App\Services\Archive;

use App\Models\ArchiveDette;
use MongoDB\Client as MongoClient;

class MongoDBArchiveService implements ArchiveServiceInterface
{
    protected $client;
    protected $database;

    public function __construct($dsn, $db)
    {
        $this->client = new MongoClient($dsn);
        $this->database = $db;
    }

    public function archiveDette(ArchiveDette $dette): void
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        $collection->insertOne($dette->toArray());
    }

    public function getAll()
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        return $collection->find()->toArray();
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

    public function restoreByDate($date)
    {
        // Logic to retrieve and delete dettes by date
    }

    public function restoreById($id)
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        $collection->deleteOne(["_id" => new \MongoDB\BSON\ObjectId($id)]);
    }

    public function restoreByClient($clientId)
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        $collection->deleteMany(["client_id" => $clientId]);
    }
}
