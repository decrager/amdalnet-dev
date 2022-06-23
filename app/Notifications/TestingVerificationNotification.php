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
    public $name;
    public $role;
    public $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TestingVerification $testingVerification, $name, $role, $type)
    {
        $this->testingVerification = $testingVerification;
        $this->name = $name;
        $this->role = $role;
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
        $path = '#';

        if($this->type == 'pemeriksaan') {
            if($this->role == 'valadm') {
                $path = '/amdal' . '/' . $this->testingVerification->id_project . '/uji-berkas-administrasi-ka';
            }
        }

        return [
            'testingVerification' => $this->testingVerification,
            'user' => $notifiable,
            'message' => $this->getMessage(),
            'path' => $path,
        ];
    }

    private function getMessage() {
        $message = '';

        if($this->type == 'pemeriksaan') {
            if($this->testingVerification->document_type == 'ka') {
                $message = 'Halo ' . $this->name . ', Formulir Kerangka Acuan dengan nama usaha/kegiatan ' . $this->testingVerification->project->project_title . ' sedang dilakukan pemeriksaan berkas kelengkapan Formulir Kerangka Acuan. Kami akan segera memberikan info selanjutnya.';
            }
        } else if($this->type == 'selesai') {
            if($this->testingVerification->document_type == 'ka') {
                if($this->testingVerification->is_complete) {
                    $message = 'Selamat ' . $this->name . ', berkas Formulir Kerangka Acuan dengan nama ' . $this->testingVerification->project->project_title . ' anda sudah sesuai.';
                } else {
                    $message = 'Halo ' . $this->name . ', mohon maaf setelah hasil rapat dapat kami simpulkan bahwa Formulir Kerangka Acuan dengan nama ' . $this->testingVerification->project->project_title . ' anda akan dikembalikan karena berkas Formulir Kerangka Acuan anda tidak lengkap / salah, silahkan lakukan perbaikan dan kirim kembali, terimakasih.';
                }
            }
        }

        return $message;
    }
}
