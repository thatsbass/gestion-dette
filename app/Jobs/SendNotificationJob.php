<?php

namespace App\Jobs;

use App\Services\Notification\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientId;
    protected $selectedClients;

    /**
     * Create a new job instance.
     *
     * @param int|null $clientId The ID of a single client to notify (optional)
     * @param array|null $selectedClients An array of selected client IDs (optional)
     */

    public function __construct($clientId = null, array $selectedClients = null)
    {
        $this->clientId = $clientId;
        $this->selectedClients = $selectedClients;
    }

    /**
     * Execute the job.
     */

    public function handle(NotificationService $notificationService)
    {
        if ($this->clientId) {
            // If a single client ID is provided, send notification to that client
            $notificationService->sendNotificationToClient($this->clientId);
        } elseif ($this->selectedClients) {
            // If an array of selected clients is provided, send notifications to them
            $notificationService->sendNotificationsToSelectedClients(
                $this->selectedClients
            );
        } else {
            // If no specific client is provided, send notifications to all clients
            $notificationService->sendNotificationsToAllClients();
        }
    }
}
