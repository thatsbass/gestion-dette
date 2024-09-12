<?php

namespace App\Providers;

use App\Services\Archive\ArchiveServiceInterface;
use App\Services\Archive\MongoDBArchiveService;
use App\Services\Archive\FirebaseArchiveService;
use Illuminate\Support\ServiceProvider;
use Log;

class ArchiveServiceProvider extends ServiceProvider
{
    
        public function register()
        {
            $driver = config('archive.driver'); 
    
            $this->app->bind(ArchiveServiceInterface::class, function ($app) use ($driver) {
                if ($driver === 'firebase') {
                    Log::info('Utilisation du service Firebase pour l\'archivage.');
                    return new FirebaseArchiveService();
                }
                return new MongoDBArchiveService();
            });
        }
    }
    
    
