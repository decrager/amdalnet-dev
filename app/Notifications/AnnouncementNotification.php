<?php

namespace App\Notifications;

use App\Entity\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnnouncementNotification extends Notification
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
        return ['mail'];
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
                    ->subject('Publikasi Pengumuman Rencana Usaha dan/atau Kegiatan')
                    ->line('Halo ' . $notifiable->name . ', Rencana Usaha dan/atau Kegiatan ' . $this->project->project_title . ' berhasil dipublikasi di Landing Page Amdalnet. Sekarang Anda dapat menerima Saran, Pendapat, dan Tanggapan dari masyarakat terhadap rencana usaha dan/atau kegiatan tersebut.');
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
            'message' => 'Halo ' . $notifiable->name . ', Rencana Usaha dan/atau Kegiatan ' . $this->project->project_title . ' berhasil dipublikasi di Landing Page Amdalnet. Sekarang Anda dapat menerima Saran, Pendapat, dan Tanggapan dari masyarakat terhadap rencana usaha dan/atau kegiatan tersebut.',
        ];
    }
}
