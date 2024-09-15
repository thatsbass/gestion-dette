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
use App\Notifications\MessageNotification;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $detteService = app(DetteService::class);
        $messageService = app(MessagingServiceInterface::class);
        $totalDueByClient = $detteService->getTotalDueByClient()->toArray();

        foreach ($totalDueByClient as $clientId => $totalDue) {
            $client = Client::find($clientId);
            if ($client) {
                $message = "Cher(e) {$client->surnom}, vous devez un montant total de {$totalDue} CFA. Veuillez régler votre dette. Merci";
                try {
                    $messageService->sendMessage($client->telephone, $message);
                    Log::info("Sending message ...");
                    // Envoyer la notification
                    $client->notify(new MessageNotification($message));
                } catch (\Exception $e) {
                    Log::error(
                        "Error while sending message to " .
                            $client->telephone .
                            ": " .
                            $e->getMessage()
                    );
                }
            }
        }
    }
}
