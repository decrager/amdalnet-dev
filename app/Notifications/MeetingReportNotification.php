<?php

namespace App\Notifications;

use App\Entity\MeetingReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingReportNotification extends Notification
{
    use Queueable;

    public $meetingReport;
    public $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MeetingReport $meetingReport, $type)
    {
        $this->meetingReport = $meetingReport;
        $this->type = $type;
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
        $message = '';
        if($this->type == 'disetujui') {
            $message = 'Selamat ' . $notifiable->name . ', Berita Acara dengan nama kegiatan ' . $this->meetingReport->project->project_title . ' anda sudah disetujui.';
        }

        return [
            'meeting' => $this->meetingReport ,
            'member' => $notifiable,
            'message' => $message,
            'path' => '#',
        ];
    }
}
