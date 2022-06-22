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
    public $name;
    public $role;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AppKaReview $kaReviews, $document_type, $name, $role)
    {
        $this->kaReviews = $kaReviews;
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
        $path = '#';
        if($this->role == 'pemrakarsa' || $this->role == 'penyusun') {
            if($this->document_type == 'KA') {
                $path = '/amdal' . '/' . $this->kaReviews->id_project . '/dokumen';
            } else if($this->document_type == 'ANDAL RKL RPL') {
                $path = '/amdal' . '/' . $this->kaReviews->id_project . '/dokumen-andal-rkl-rpl';
            } else if($this->document_type == 'UKL UPL') {
                $path = '/uklupl' . '/' . $this->kaReviews->id_project . '/dokumen';
            }
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

        // if($this->kaReviews->status == 'submit-to-pemrakarsa') {
        //     $message = 'Penyusun telah selesai menyusun ' . $this->formulirOrDokumen() . ' ' . $this->document_type . ' untuk direview';
        // } else if($this->kaReviews->status == 'revisi') {
        //     $message = 'Pemrakarsa mengembalikan ' . $this->formulirOrDokumen() . ' ' . $this->document_type . ' untuk direvisi';
        // } else if($this->kaReviews->status == 'submit') {
        //     $message = 'Pemrakara telah mereview ' . $this->formulirOrDokumen() . ' '  . $this->document_type . ' untuk dinilai';
        // }

        if($this->document_type == 'KA') {
            $message = 'Penyusunan Formulir Kerangka Acuan';
        } else if($this->document_type == 'ANDAL RKL RPL') {
            $message = 'Andal RKL RPL';
        } else if($this->document_type == 'UKL UPL') {
            $message = 'Penyusunan Formulir UKL-UPL';
        }

        return "Halo " . $this->name . ', ' . $message . ' dengan nama usaha/kegiatan ' . $this->kaReviews->project->project_title . ' berhasil terkirim.';
    }

    private function formulirOrDokumen()
    {
        if($this->document_type == 'ANDAL RKL RPL') {
            return 'Dokumen';
        } else {
            return 'Formulir';
        }
    }
}
