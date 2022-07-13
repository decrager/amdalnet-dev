<?php

namespace App\Notifications;

use App\Entity\FormulatorTeam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormulatorTeamAssigned extends Notification
{
    use Queueable;

    public $formulatorTeam;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FormulatorTeam $formulatorTeam)
    {
        $this->formulatorTeam = $formulatorTeam;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Tim Penyusun Kegiatan')
                    ->line('Anda baru saja dimasukkan ke '.$this->formulatorTeam->name);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'formulatorTeam' => $this->formulatorTeam,
            'formulator' => $notifiable,
            'message' => 'Anda baru saja dimasukkan ke '.$this->formulatorTeam->name,
            'path' => '/project',
        ];
    }
}
