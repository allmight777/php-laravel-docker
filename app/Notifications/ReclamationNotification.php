<?php

namespace App\Notifications;

use App\Models\Reclamation;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReclamationNotification extends Notification
{
    protected $reclamation;

    public function __construct(Reclamation $reclamation)
    {
        $this->reclamation = $reclamation;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle réclamation sur ' . $this->reclamation->matiere->nom)
            ->line('Une nouvelle réclamation a été soumise par lélève : ' . $this->reclamation->eleve->user->nom)
            ->line('Message : ' . $this->reclamation->message)
            ->action('Voir la réclamation', url('/reclamations'))
            ->line('Merci de traiter cette réclamation rapidement.');
    }

    public function toArray($notifiable)
    {
        return [
            'reclamation_id' => $this->reclamation->id,
            'message' => 'Réclamation reçue sur ' . $this->reclamation->matiere->nom,
            'url' => '/reclamations'
        ];
    }
}