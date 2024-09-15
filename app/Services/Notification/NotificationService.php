<?php

namespace App\Services\Notification;

use App\Services\DetteService;
use App\Services\Messaging\MessagingServiceInterface;
use App\Models\Client;
use Log;
use App\Notifications\MessageNotification;

class NotificationService
{
    protected $detteService;
    protected $messageService;

    public function __construct(
        DetteService $detteService,
        MessagingServiceInterface $messageService
    ) {
        $this->detteService = $detteService;
        $this->messageService = $messageService;
    }

    /**
     * Send notification to a single client.
     */
    public function sendNotificationToClient($clientId)
    {
        // Get the total amount due for the specific client
        $totalDue = $this->detteService->getTotalDueClientById($clientId);

        // Find the client
        $client = Client::find($clientId);

        if ($client) {
            $message = "Cher(e) {$client->surnom}, vous devez un montant total de {$totalDue} CFA. Veuillez régler votre dette. Merci";
            $this->sendMessageAndLog($client, $message);
        }
    }

    /**
     * Send notifications to all clients with due payments.
     */
    public function sendNotificationsToAllClients()
    {
        // Get all clients with their total due amounts
        $clientsWithDue = $this->detteService->getTotalDueByClient();

        foreach ($clientsWithDue as $clientId => $totalDue) {
            $client = Client::find($clientId);
            if ($client) {
                $message = "Cher(e) {$client->surnom}, vous devez un montant total de {$totalDue} CFA. Veuillez régler votre dette. Merci";
                $this->sendMessageAndLog($client, $message);
            }
        }
    }

    /**
     * Send notifications to selected clients.
     */
    public function sendNotificationsToSelectedClients(array $clientIds)
    {
        // Get selected clients with their total due amounts
        $clientsWithDue = $this->detteService->getTotalDueByClientSelected(
            $clientIds
        );

        foreach ($clientsWithDue as $clientId => $totalDue) {
            $client = Client::find($clientId);
            if ($client) {
                $message = "Cher(e) {$client->surnom}, vous devez un montant total de {$totalDue} CFA. Veuillez régler votre dette. Merci";
                $this->sendMessageAndLog($client, $message);
            }
        }
    }

    /**
     * Helper method to send a message and log the notification.
     */
    protected function sendMessageAndLog(Client $client, $message)
    {
        try {
            // Send the message using the messaging service
            $this->messageService->sendMessage($client->telephone, $message);
            Log::info("Sending message to {$client->telephone}...");

            // Notify the client using the built-in Laravel notification system
            $client->notify(new MessageNotification($message));
        } catch (\Exception $e) {
            // Log any errors that occur during the sending process
            Log::error(
                "Error while sending message to " .
                    $client->telephone .
                    ": " .
                    $e->getMessage()
            );
        }
    }
}
