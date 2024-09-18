<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DemandeAnnuleeNotification extends Notification
{
    protected $motif;

    public function __construct($motif)
    {
        $this->motif = $motif;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Demande Annulée')
            ->line('Votre demande a été annulée.')
            ->line('Motif : ' . $this->motif);
    }
}
