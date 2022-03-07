<?php

namespace App\Notifications;

use App\Laravue\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification
{
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
        return ['mail','database'];
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
        $raw_password = '';
        if (property_exists($this->user, 'raw_password')) {
            $raw_password = $this->user->getRawPassword();
        }
        if (!empty($raw_password)) {
            return (new MailMessage)
                            ->subject('Pendaftaran Akun AMDALNET')
                            ->greeting('Akun AMDALNET Anda Berhasil Dibuat.')
                            ->line('Username: ' . $this->user->name)
                            ->line('Password: ' . $raw_password)
                            ->line('')
                            ->line('Hai '.$this->user->name)
                            ->line('')
                            ->line('Akun AMDALNET anda telah berhasil dibuat.');
        } else {
            return (new MailMessage)
                            ->subject('Pendaftaran Akun AMDALNET')
                            ->greeting('Akun AMDALNET Anda Berhasil Dibuat.')
                            ->line('Hai '.$this->user->name)
                            ->line('')
                            ->line('Akun AMDALNET anda telah berhasil dibuat silahkan aktivasi akun dengan menekan tombol dibawah ini.')
                            ->action('Aktivasi Akun Anda', $url);
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
        return [
            'createdUser' => $this->user,
            'admin' => $notifiable,
            'message' => $this->user->name.' berhasil mendaftar dengan email '.$this->user->email,
        ];
    }
}
