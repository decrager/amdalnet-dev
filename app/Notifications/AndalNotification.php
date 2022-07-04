<?php

namespace App\Notifications;

use App\Entity\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AndalNotification extends Notification
{
    use Queueable;
    public $project;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $path = '#';
        if($notifiable->roles->first()->name == 'formulator') {
            $path = '/amdal' . '/' . $this->project->id . '/penyusunan-andal';
        }

        return [
            'project' => $this->project ,
            'member' => $notifiable,
            'message' => 'Halo ' . $notifiable->name .', Kegiatan dengan nama ' . $this->project->project_title . ' sudah bisa dilakukan pengisian Andal.',
            'path' => $path
        ];
    }
}
