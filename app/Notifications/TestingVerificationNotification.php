<?php

namespace App\Notifications;

use App\Entity\TestingVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestingVerificationNotification extends Notification
{
    use Queueable;

    public $testingVerification;
    public $document_type;
    public $name;
    public $role;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TestingVerification $testingVerification, $document_type, $name, $role)
    {
        $this->testingVerification = $testingVerification;
        $this->document_type = $document_type;
        $this->name = $name;
        $this->role = $role;
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
        $path = '#';

        if($this->document_type == 'ka') {
            $message = 'Halo ' . $this->name . ', Formulir Kerangka Acuan dengan nama usaha/kegiatan ' . $this->testingVerification->project->project_title . ' sedang dilakukan pemeriksaan berkas kelengkapan Formulir Kerangka Acuan. Kami akan segera memberikan info selanjutnya.';
            if($this->role == 'valadm') {
                $path = '/amdal' . '/' . $this->testingVerification->id_project . '/uji-berkas-administrasi-ka';
            }
        }

        return [
            'testingVerification' => $this->testingVerification,
            'user' => $notifiable,
            'message' => $message,
            'path' => $path,
        ];
    }
}
