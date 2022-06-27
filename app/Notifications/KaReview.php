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
        $path = '#';
        $role = $notifiable->roles->first()->name;
        if($role == 'initiator' || $role == 'formulator') {
            if($this->kaReviews->document_type == 'ka') {
                $path = '/amdal' . '/' . $this->kaReviews->id_project . '/dokumen';
            } else if($this->kaReviews->document_type == 'andal-rkl-rpl') {
                $path = '/amdal' . '/' . $this->kaReviews->id_project . '/dokumen-andal-rkl-rpl';
            } else if($this->kaReviews->document_type == 'ukl-upl') {
                $path = '/uklupl' . '/' . $this->kaReviews->id_project . '/dokumen';
            }
        }

        return [
            'kaReview' => $this->kaReviews,
            'user' => $notifiable,
            'message' => $this->getMessage($notifiable),
            'path' => $path,
        ];
    }

    private function getMessage($notifiable)
    {
        $message = '';

        // if($this->kaReviews->status == 'submit-to-pemrakarsa') {
        //     $message = 'Penyusun telah selesai menyusun ' . $this->formulirOrDokumen() . ' ' . $this->document_type . ' untuk direview';
        // } else if($this->kaReviews->status == 'revisi') {
        //     $message = 'Pemrakarsa mengembalikan ' . $this->formulirOrDokumen() . ' ' . $this->document_type . ' untuk direvisi';
        // } else if($this->kaReviews->status == 'submit') {
        //     $message = 'Pemrakara telah mereview ' . $this->formulirOrDokumen() . ' '  . $this->document_type . ' untuk dinilai';
        // }

        if($this->kaReviews->status == 'submit') {
            if($this->kaReviews->document_type == 'ka') {
                $message = 'Penyusunan Formulir Kerangka Acuan';
            } else if($this->kaReviews->document_type == 'andal-rkl-rpl') {
                $message = 'Andal RKL RPL';
            } else if($this->kaReviews->document_type == 'ukl-upl') {
                $message = 'Penyusunan Formulir UKL UPL';
            }
    
            return "Halo " . $notifiable->name . ', ' . $message . ' dengan nama usaha/kegiatan ' . $this->kaReviews->project->project_title . ' berhasil terkirim.';
        } else {
            if($this->kaReviews->document_type == 'ka') {
                $message = 'Formulir Kerangka Acuan';
            } else if($this->kaReviews->document_type == 'andal-rkl-rpl') {
                $message = 'Andal RKL RPL';
            } else if($this->kaReviews->document_type == 'ukl-upl') {
                $message = 'UKL UPL';
            }

            return 'Selamat ' . $notifiable->name . ', ' . $message . ' dengan nama kegiatan ' . $this->kaReviews->project->project_title . ' sudah selesai.';
        }
    }

    private function formulirOrDokumen()
    {
        if($this->kaReviews->document_type == 'andal-rkl-rpl') {
            return 'Dokumen';
        } else {
            return 'Formulir';
        }
    }
}
