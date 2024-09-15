<?php

namespace App\Jobs;

use App\Services\Notification\NotificationService;
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
    protected $selectedClients;
    protected $customMessage;

    public function __construct(
        $clientId = null,
        $selectedClients = [],
        $customMessage = null
    ) {
        $this->clientId = $clientId;
        $this->selectedClients = $selectedClients;
        $this->customMessage = $customMessage;
    }

    public function handle()
    {
        $notificationService = app(NotificationService::class);

        if ($this->clientId) {
            $totalDue = $notificationService->getTotalDueClientById(
                $this->clientId
            );
            $client = Client::find($this->clientId);
            if ($client) {
                $message = "Cher(e) {$client->surnom}, vous devez un montant total de {$totalDue} CFA. Veuillez régler votre dette. Merci";
                $this->sendNotification($client, $message);
            }
        } elseif (!empty($this->selectedClients)) {
            foreach ($this->selectedClients as $clientData) {
                $clientId = $clientData["id"];
                $totalDue = $notificationService->getTotalDueClientById(
                    $clientId
                );
                $client = Client::find($clientId);
                if ($client) {
                    $message =
                        $this->customMessage ??
                        "Cher(e) {$client->surnom}, vous devez un montant total de {$totalDue} CFA. Veuillez régler votre dette. Merci";
                    $this->sendNotification($client, $message);
                }
            }
        }
    }

    protected function sendNotification($client, $message)
    {
        try {
            // Send notification through SMS service
            $notificationService = app(NotificationService::class);
            $notificationService->sendMessage($client->telephone, $message);
            Log::info("Sending message to {$client->telephone}...");
            // Store notification in database
            $client->notify(new MessageNotification($message));
        } catch (\Exception $e) {
            Log::error(
                "Error while sending message to {$client->telephone}: {$e->getMessage()}"
            );
        }
    }
}
