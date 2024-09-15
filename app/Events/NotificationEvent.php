<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent
{
    use Dispatchable, SerializesModels;

    public $clientId;
    public $selectedClients;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param int|null $clientId
     * @param array|null $selectedClients
     * @param string|null $message
     */
    public function __construct(
        $clientId = null,
        array $selectedClients = null,
        $message = null
    ) {
        $this->clientId = $clientId;
        $this->selectedClients = $selectedClients;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new Channel("notifications");
    }
}
