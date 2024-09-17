<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DemandeNotification extends Notification
{
    use Queueable;

    protected $demande;

    public function __construct($demande)
    {
        $this->demande = $demande;
    }

    public function via($notifiable)
    {
        return ['database']; // S'assurer que 'database' est bien présent
    }

    public function toDatabase($notifiable)
    {
        return [
            'demande_id' => $this->demande->id,
            'montant' => $this->demande->montant,
            // Ajouter d'autres champs si nécessaire
        ];
    }
}
