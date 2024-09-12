<?php

namespace App\Services\Archive;

use App\Models\Dette;
use MongoDB\Client as MongoClient;
use Log;

class MongoDBArchiveService implements ArchiveServiceInterface
{
    protected $client;

    public function __construct()
    {
        Log::info('MongoDBArchiveService instancié');
    
        $this->client = new MongoClient(config('database.connections.mongodb.dsn'));
    }
    
    public function archiveDette(Dette $dette): void
    {
        // Charger les relations de la dette
        $dette->load('articles', 'paiements');

        $collection = $this->client->selectCollection('archive', 'dettes');
        $collection->insertOne([
            'client_id' => $dette->client_id,
            'montant' => $dette->montant,
            'articles' => $dette->articles->toArray(), 
            'paiements' => $dette->paiements->toArray(),
            'archived_at' => now(),
        ]);
    }
}
