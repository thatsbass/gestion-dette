<?php

namespace App\Jobs;

use App\Models\Demande;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DemandeNotification;
use Log;
class NotifyBoutiquiersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $demande;
    public function __construct(Demande $demande)
    {
        $this->demande = $demande;
    }

    public function handle()
    {
        $boutiquiers = User::where("role_id", 2)->get();
        
        foreach ($boutiquiers as $boutiquier) {
            Log::info("Notification envoyée au boutiquier: " . $boutiquier->id);     
            $boutiquier->notify(new DemandeNotification($this->demande));
        }
    }
    
    
}
