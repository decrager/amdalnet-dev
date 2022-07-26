<?php

namespace App\Notifications;

use App\Entity\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeAuthorityNotification extends Notification
{
    use Queueable;

    public $project;
    public $notes;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project, $notes)
    {
        $this->project = $project;
        $this->notes = $notes;
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
                    ->subject('Perubahan Kewenangan')
                    ->greeting('Halo ' . $notifiable->name . ',')
                    ->line($this->notes);
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
            'project' => $this->project,
            'secretaryChief' => $notifiable,
            'message' => $this->notes,
            'path' => '/project'
        ];
    }
}
