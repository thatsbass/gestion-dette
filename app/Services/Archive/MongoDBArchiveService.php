<?php

namespace App\Services\Archive;

use App\Models\Dette;
use MongoDB\Client as MongoClient;
use MongoDB\BSON\UTCDateTime;
use Carbon\Carbon;
use Log;

class MongoDBArchiveService implements ArchiveServiceInterface
{
    protected $client;
    protected $database;

    public function __construct($dsn, $db)
    {
        $this->client = new MongoClient($dsn);
        $this->database = $db;
    }

    public function archiveDette(Dette $dette)
    {
        Log::info("La dette en cours vers mongo : ".$dette);
      
        $dette->load("articles", "paiements");

        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
      
    $createdAt = new UTCDateTime($dette->created_at->getTimestamp() * 1000);
    $updatedAt = new UTCDateTime($dette->updated_at->getTimestamp() * 1000);
    $archivedAt = new UTCDateTime(now()->timestamp * 1000);
   

    $collection->insertOne([
        "dette_id" => $dette->id,
        "client_id" => $dette->client_id,
        "montant" => $dette->montant,
        "articles" => $dette->articles->toArray(),
        "paiements" => $dette->paiements->toArray(),
        "limit_at" => $dette->limit_at,
        "created_at" => $createdAt,
        "updated_at" => $updatedAt,
        "archived_at" => $archivedAt,
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

   
    public function getById($detteId)
{
    $collection = $this->client->selectCollection($this->database, "dettes");

    $document = $collection->findOne(["dette_id" => (int) $detteId]);
    if ($document) {
        return $this->convertBSONToArray($document);
    }
    return null;
}

    public function convertBSONToArray($document)
    {
        $array = json_decode(json_encode($document), true);
        function convertToDateTime($value)
        {
            
            if (is_string($value) && strtotime($value)) {
                return new \DateTime($value);
            }
            return $value;
        }
        if (isset($array['created_at'])) {
            $array['created_at'] = convertToDateTime($array['created_at']);
        }
        if (isset($array['updated_at'])) {
            $array['updated_at'] = convertToDateTime($array['updated_at']);
        }
        if (isset($array['archived_at'])) {
            $array['archived_at'] = convertToDateTime($array['archived_at']);
        }
    
        return $array;
    }
    




    

    public function deleteById($detteId)
    {
        $collection = $this->client->selectCollection(
            $this->database,
            "dettes"
        );
        $collection->deleteOne([
            "dette_id" => (int) $detteId,
        ]);
    }
}
