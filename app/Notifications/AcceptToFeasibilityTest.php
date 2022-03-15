<?php

namespace App\Notifications;

use App\Entity\MeetingReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptToFeasibilityTest extends Notification
{
    use Queueable;
    public $meeting_report;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MeetingReport $meetingReport)
    {
        $this->meeting_report = $meetingReport;
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
        return [
            'meetingReport' => $this->meeting_report,
            'user' => $notifiable,
            'message' => $this->getMessage(),
            'path' => '#',
        ];
    }

    private function getMessage()
    {
        if($this->meeting_report->is_accepted) {
            return 'Kegiatan ' . $this->meeting_report->project->project_title . ' akan diteruskan oleh TUK untuk dilakukan uji kelayakan';
        } else {
            return 'Kegiatan ' . $this->meeting_report->project->project_title . ' tidak akan diteruskan oleh TUK untuk dilakukan uji kelayakan';
        }
    }
}
