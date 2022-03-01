<?php

namespace App\Notifications;

use App\Entity\KaReview as AppKaReview;
use App\Entity\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KaReview extends Notification
{
    use Queueable;

    public $kaReviews;
    public $document_type;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AppKaReview $kaReviews, $document_type)
    {
        $this->kaReviews = $kaReviews;
        $this->document_type = $document_type;
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
        if($this->document_type == 'KA') {
            $path = '/amdal' . '/' . $this->kaReviews->id_project . '/dokumen';
        } else if($this->document_type == 'ANDAL') {
            $path = '/dokumen-kegiatan' . '/' . $this->kaReviews->id_project . '/penyusunan-andal';
        } else if($this->document_type == 'RKL RPL') {
            $path = '/dokumen-kegiatan' . '/' . $this->kaReviews->id_project . '/penyusunan-rkl-rpl';
        } else if($this->document_type == 'UKL UPL') {
            $path = '/uklupl' . '/' . $this->kaReviews->id_project . '/dokumen';
        }

        return [
            'kaReview' => $this->kaReviews,
            'user' => $notifiable,
            'message' => $this->getMessage(),
            'path' => $path,
        ];
    }

    private function getMessage()
    {
        $message = '';

        if($this->kaReviews->status == 'submit-to-pemrakarsa') {
            $message = 'Penyusun telah selesai menyusun Formulir ' . $this->document_type . ' untuk direview';
        } else if($this->kaReviews->status == 'revisi') {
            $message = 'Pemrakarsa mengembalikan Formulir ' . $this->document_type . ' untuk direvisi';
        }

        return $message;
    }
}
