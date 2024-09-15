<?php

namespace App\Services\Notification;

use App\Services\DetteService;
use App\Services\Messaging\MessagingServiceInterface;
use App\Models\Client;
use Log;
use App\Notifications\MessageNotification;

class NotificationService
{
    protected $messagingService;

    public function __construct(MessagingServiceInterface $messagingService)
    {
        $this->messagingService = $messagingService;
    }
    public function sendMessage($telephone, $message)
    {
        try {
            $this->messagingService->sendMessage($telephone, $message);
        } catch (\Exception $e) {
            Log::error("Error sending message: {$e->getMessage()}");
        }
    }

    public function getTotalDueClientById($id)
    {
        $detteService = app(DetteService::class);
        return $detteService->getTotalDueClientById($id);
    }

    public function getTotalDueByClientSelected(array $clientIds)
    {
        $detteService = app(DetteService::class);
        return $detteService->getTotalDueByClientSeleted($clientIds);
    }
}
