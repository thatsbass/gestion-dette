<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeValideeNotification extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Demande Validée')
            ->line('Votre demande a été validée.')
            ->line('Veuillez passer à la boutique pour récupérer les produits.');
    }
}
