<?php

namespace App\Events;
use Illuminate\Queue\SerializesModels;

class NotificationEvent
{
    use SerializesModels;

    public $clientId;
    public $type;
    public $selectedClients;
    public $customMessage;

    public function __construct(
        $clientId = null,
        $type,
        $selectedClients = [],
        $customMessage = null
    ) {
        $this->clientId = $clientId;
        $this->type = $type;
        $this->selectedClients = $selectedClients;
        $this->customMessage = $customMessage;
    }
}
