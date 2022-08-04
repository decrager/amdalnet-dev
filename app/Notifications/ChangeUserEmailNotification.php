<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeUserEmailNotification extends Notification
{
    use Queueable;

    public $name;
    public $email;
    public $role;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name = null, $email = null, $role = null)
    {   
        $this->name = $name;
        $this->email = $email;
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
        $name = $this->name ? $this->name : $notifiable->name;
        $role = $this->role ? $this->role : $notifiable->roles->first()->name;
        $with_email = $this->email ? ' menjadi ' . $this->email : '';

        return (new MailMessage)
                    ->subject('Pemberitahuan Perubahan Email')
                    ->greeting('Halo ' . $name)
                    ->line('Akun Email ' . $this->getRoleName($role) . ' anda telah berhasil diubah' . $with_email);
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

    private function getRoleName($user_role)
    {
        $role = '';
        switch ($user_role) {
            case 'formulator':
                $role = 'Penyusun';
                break;
            case 'lpjp':
                $role = 'LPJP';
                break;
            default:
                break;
        }

        return $role;
    }
}
