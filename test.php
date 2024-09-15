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

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientId;
    protected $is_selected;

    public function __construct(
        ?int $clientId = null,
        ?array $data_client_selected = null
    ) {
        $this->clientId = $clientId;
        $this->is_selected = $data_client_selected;
    }

    public function handle()
    {
        $detteService = app(DetteService::class);
        $messageService = app(MessagingServiceInterface::class);

        $clientsWithDue = [];

        if ($this->clientId) {
            $clientsWithDue = $detteService->getTotalDueByClient([
                $this->clientId,
            ]);
        } elseif ($this->is_selected) {
            $clientsWithDue = $detteService->getDueClientSelect(
                $this->is_selected
            );
        } else {
            $clientsWithDue = $detteService->getTotalDueByClient();
        }

        foreach ($clientsWithDue as $clientId => $totalDue) {
            $client = Client::find($clientId);
            if ($client) {
                $message = "Cher(e) {$client->surnom}, vous devez un montant total de {$totalDue} CFA. Veuillez régler votre dette. Merci";
                try {
                    $messageService->sendMessage($client->telephone, $message);
                    Log::info("Sending message to {$client->telephone}...");
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

use App\Http\Controllers\NotificationController;

Route::prefix("notification")->group(function () {
    Route::get("client/{id}", [
        NotificationController::class,
        "notifyingleClient",
    ]);
    Route::post("client/all", [
        NotificationController::class,
        "notifyForClientsSelected",
    ]);
    Route::post("client/message", [
        NotificationController::class,
        "sendCustomMessageForClientsSelected",
    ]);
});
