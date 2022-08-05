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
            case 'admin':
                $role = 'Admin';
                break;
            case 'initiator':
                $role = 'Pemrakarsa';
                break;
            case 'formulator':
                $role = 'Penyusun';
                break;
            case 'institution':
                $role = 'Institusi';
                break;
            case 'admin-central':
                $role = 'Admin Pusat';
                break;
            case 'admin-system':
                $role = 'Admin Sistem';
                break;
            case 'admin-standard':
                $role = 'Admin Standar';
                break;
            case 'admin-regional':
                $role = 'Admin Wilayah';
                break;
            case 'lpjp':
                $role = 'LPJP';
                break;
            case 'examiner':
                $role = 'Ahli';
                break;
            case 'examiner-substance':
                $role = 'Ahli';
                break;
            case 'examiner-secretary':
                $role = 'Ahli';
                break;
            case 'examiner-administration':
                $role = 'Ahli';
                break;
            case 'examiner-institution':
                $role = 'Institusi Ahli';
                break;
            case 'examiner-chief':
                $role = 'Ketua TUK';
                break;
            case 'examiner-secretary':
                $role = 'Kepala Sekretariat TUK';
                break;
            case 'examiner-community':
                $role = 'TUK Masyarakat';
                break;
            case 'public':
                $role = 'Masyarakat';
                break;
            case 'formulator-chief':
                $role = 'Ketua Penyusun';
                break;
            case 'formulator-member':
                $role = 'Anggota Tim Penyusun';
                break;
            case 'formulator-expert':
                $role = 'Anggota Ahli Tim Penyusun';
                break;
            case 'pustanling':
                $role = 'Admin Pustanling';
                break;
            case 'luk':
                $role = 'Lembaga Uji Kelayakan';
                break;
            default:
                break;
        }

        return $role;
    }
}
