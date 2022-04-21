<?php

namespace App\Notifications;

use App\Entity\TestingMeeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingInvitation extends Notification
{
    use Queueable;

    public $meeting;
    public $notification_type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TestingMeeting $meeting, $notification_type)
    {
        $this->meeting = $meeting;
        $this->notification_type = $notification_type;
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
        if($this->notification_type == 'tuk') {
            return (new MailMessage)
                        ->subject('Undangan Rapat Pembahasan ' . $this->documentType())
                        ->line('Anda diundang rapat pembahasan ' . $this->documentType() . ' untuk kegiatan ' . $this->meeting->project->project_title)
                        ->attach(storage_path('app/public/' . $this->docxName()));
        } else {
            return (new MailMessage)
                        ->subject('Pemberitahuan Rapat Pembahasan ' . $this->documentType())
                        ->line('Kegiatan ' . $this->meeting->project->project_title . ' akan dilakukan pembahasan ' . $this->documentType() . ' oleh Tim Uji Kelayakan');
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
        if($this->notification_type == 'tuk') {
            return [
                'meeting' => $this->meeting ,
                'member' => $notifiable,
                'message' => 'Anda diundang rapat pembahasan ' . $this->documentType() . ' untuk kegiatan ' . $this->meeting->project->project_title,
                'path' => '#',
            ];
        } else {
            return [
                'meeting' => $this->meeting ,
                'member' => $notifiable,
                'message' => 'Kegiatan ' . $this->meeting->project->project_title . ' akan dilakukan pembahasan ' . $this->documentType() . ' oleh Tim Uji Kelayakan',
                'path' => '#',
            ];
        }
    }

    private function documentType()
    {
        $document_type = '';
        if($this->meeting->document_type == 'ka') {
            $document_type = 'Kerangka Acuan';
        } else if($this->meeting->document_type == 'rkl-rpl') {
            $document_type = 'Analisis Dampak Lingkungan dan Rencana Pengelolaan Hidup dan Rencana Pemantauan Lingkungan Hidup';
        } else if($this->meeting->document_type == 'ukl-upl') {
            $document_type = 'Upaya Pengelolaan Lingkungan Hidup dan Upaya Pemantauan Lingkungan Hidup';
        }

        return $document_type;
    }

    private function docxName() {
        $name = '';
        if($this->meeting->document_type == 'ka') {
           $name = 'meeting-ka/' . strtolower($this->meeting->project->project_title) . '.pdf';
        } else if($this->meeting->document_type == 'rkl-rpl') {
            $name = 'meeting-andal-rkl-rpl/' . strtolower($this->meeting->project->project_title) . '.pdf';
        } else if($this->meeting->document_type == 'ukl-upl') {
            $name = 'meeting-ukl-upl/' . strtolower($this->meeting->project->project_title) . '.pdf';
        }

        return $name;
    }
}
