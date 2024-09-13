<?php

namespace App\Services\Archive;

use App\Models\Dette;
use Kreait\Firebase\Factory;
use Log;

class FirebaseArchiveService implements ArchiveServiceInterface
{
    protected $database;

    public function __construct()
    {
       
        $firebaseConfig = config('database.connections.firebase');
        $factory = (new Factory)
            ->withServiceAccount($firebaseConfig['credentials'])
            ->withDatabaseUri($firebaseConfig['database_url']);
        $this->database = $factory->createDatabase();
    }
    

    public function archiveDette(Dette $dette): void
    {
        
        $dette->load('articles', 'paiements');

        $this->database->getReference('archive/dettes')->push([
            'client_id' => $dette->client_id,
            'montant' => $dette->montant,
            'articles' => $dette->articles->toArray(),
            'paiements' => $dette->paiements->toArray(),
            'archived_at' => now(),
        ]);
    }
}
