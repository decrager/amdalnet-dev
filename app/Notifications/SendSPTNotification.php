<?php

namespace App\Notifications;

use App\Entity\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendSPTNotification extends Notification
{
    use Queueable;
    public $feedback;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
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
                    ->subject('Saran, Pendapat, dan Tanggapan')
                    ->line('Halo ' . $this->feedback->name . ', terimakasih atas Saran, Pendapat, dan Tanggapan anda terhadap rencana usaha dan/atau kegiatan ' . $this->feedback->announcement->project->project_title);
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
            'feedback' => $this->feedback,
            'user' => $notifiable,
            'message' => 'Halo ' . $this->feedback->name . ', terimakasih atas Saran, Pendapat, dan Tanggapan anda terhadap rencana usaha dan/atau kegiatan ' . $this->feedback->announcement->project->project_title
        ];
    }
}
