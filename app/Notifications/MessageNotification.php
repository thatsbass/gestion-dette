<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class MessageNotification extends Notification
{
    use Queueable;

    protected $totalDue;

    public function __construct($totalDue)
    {
        $this->totalDue = $totalDue;
    }

    public function via($notifiable)
    {
        return ["database"];
    }

    public function toArray($notifiable)
    {
        return [
            "message" => "Rappel de dette : {$this->totalDue} CFA",
        ];
    }
}
