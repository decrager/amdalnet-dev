<?php

namespace App\Notifications;

use App\Entity\KaReview as AppKaReview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KaReview extends Notification
{
    use Queueable;

    public $kaReviews;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AppKaReview $kaReviews)
    {
        $this->kaReviews = $kaReviews;
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
            'kaReview' => $this->kaReviews,
            'user' => $notifiable,
            'message' => $this->getMessage(),
            'path' => '/#/amdal' . '/' . $this->kaReviews->id_project . '/dokumen' ,
        ];
    }

    private function getMessage()
    {
        $message = '';

        if($this->kaReviews->status == 'submit-to-pemrakarsa') {
            $message = 'Penyusun telah selesai menyusun Formulir KA untuk direview';
        } else if($this->kaReviews->status == 'revisi') {
            $message = 'Pemrakarsa mengembalikan Formulir KA untuk direvisi';
        }

        return $message;
    }
}
