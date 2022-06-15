<?php

namespace App\Notifications;

use App\Entity\Project;
use App\Laravue\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateProjectNotification extends Notification
{
    use Queueable;
    public $project;
    public $user;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project, User $user)
    {
        $this->project = $project;
        $this->user = $user;
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
                    ->subject('Pengajuan Persetujuan Lingkungan')
                    ->line('Selamat ' . $this->user->name . ' rencana usaha kegiatan Anda sudah berhasil dibuat dengan nama ' . $this->project->project_title);
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
            'createdProject' => $this->project,
            'user' => $notifiable,
            'message' => 'Selamat ' . $this->user->name . ' rencana usaha kegiatan Anda sudah berhasil dibuat dengan nama ' . $this->project->project_title,
        ];
    }
}
