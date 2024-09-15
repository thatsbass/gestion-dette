<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use App\Jobs\SendNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
class NotificationListener implements ShouldQueue
{
    /**
     * @return void
     */
    public function handle(NotificationEvent $event): void
    {
        if ($event->type === "single") {
            dispatch(new SendNotificationJob($event->clientId));
        } elseif ($event->type === "selected") {
            dispatch(new SendNotificationJob(null, $event->selectedClients));
        } elseif ($event->type === "custom") {
            dispatch(
                new SendNotificationJob(
                    null,
                    $event->selectedClients,
                    $event->customMessage
                )
            );
        }
    }
}
