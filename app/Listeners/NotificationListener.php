<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use App\Jobs\SendNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param NotificationEvent $event
     * @return void
     */
    public function handle(NotificationEvent $event)
    {
        // Dispatch the job with appropriate parameters
        SendNotificationJob::dispatch(
            $event->clientId,
            $event->selectedClients,
            $event->message
        );
    }
}
