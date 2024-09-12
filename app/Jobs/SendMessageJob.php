<?php
namespace App\Jobs;

use App\Services\DetteService;
use App\Services\Messaging\MessagingServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Client;
use Log;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $detteService = app(DetteService::class);
        $messageService = app(MessagingServiceInterface::class);
        try {
        $totalDueByClient = $detteService->getTotalDueByClient();
        log::info("". $totalDueByClient ."");
        
        foreach ($totalDueByClient as $clientId => $totalDue) {
            
            $client = Client::find($clientId);
            
            if ($client) {
                $message = "Montant total dû : " . $totalDue;
                $messageService->sendMessage($client->telephone, $message);
            }
        }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
}
}
