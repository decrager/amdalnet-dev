<?php

namespace App\Notifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Laravue\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResendVerification extends Notification
{
    // use HasFactory;
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        $url = url("/#/activate/".$this->user->id);
        return (new MailMessage)
            ->subject('Reminder Aktivasi Akun AMDALNET')
            ->greeting('Akun AMDALNET Anda Berhasil Dibuat.')
            ->line('Hai '.$this->user->name)
            ->line('')
            ->line('Akun AMDALNET anda telah berhasil dibuat silahkan aktivasi akun dengan menekan tombol dibawah ini.')
            ->action('Aktivasi Akun Anda', $url);
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
            //
        ];
    }
}
