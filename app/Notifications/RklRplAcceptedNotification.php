<?php

namespace App\Notifications;

use App\Entity\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RklRplAcceptedNotification extends Notification
{
    use Queueable;

    public $project;
    public $is_accepted;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project, $is_accepted)
    {
        $this->project = $project;
        $this->is_accepted = $is_accepted;
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
       if($this->is_accepted) {
            return (new MailMessage)
                        ->subject('Hasil Pemeriksaan Dokumen ANDAL RKL RPL')
                        ->greeting('Halo ' . $notifiable->name)
                        ->line('Selamat, Dokumen ANDAL RKL RPL dengan nama ' . $this->project->project_title . ' anda sudah disetujui.');
        } else {
            return (new MailMessage)
                        ->subject('Hasil Pemeriksaan Dokumen ANDAL RKL RPL')
                        ->greeting('Halo ' . $notifiable->name)
                        ->line('Mohon maaf setelah hasil rapat dapat kami simpulkan bahwa Dokumen ANDAL RKL RPL dengan nama ' . $this->project->project_title . ' anda akan dikembalikan, silahkan lakukan perbaikan dan kirim kembali, terimakasih.');
        }
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
            'user' => $notifiable,
            'message' => $this->getMessage($notifiable),
            'path' => '#',
        ];
    }

    private function getMessage($notifiable)
    {
        if($this->is_accepted) {
            return 'Selamat ' . $notifiable->name . ', Dokumen RKL RPL dengan nama ' . $this->project->project_title . ' anda sudah disetujui.';
        } else {
            return 'Halo ' . $notifiable->name . ', mohon maaf setelah hasil rapat dapat kami simpulkan bahwa Dokumen RKL RPL dengan nama ' . $this->project->project_title . ' anda akan dikembalikan, silahkan lakukan perbaikan dan kirim kembali, terimakasih.';
        }
    }
}
