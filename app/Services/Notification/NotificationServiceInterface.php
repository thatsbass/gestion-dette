<?php
namespace App\Services\Notification;

interface NotificationServiceInterface
{
    public function sendNotificationToClient(
        $clientId,
        $totalDue,
        $message = null
    );
    public function sendNotificationsToClients(array $clients, $message = null);
}
