<?php

namespace App\Notifications;

use App\Entity\FormulatorTeamMember;
use App\Entity\Project;
use App\Laravue\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateFormulatorTeamNotification extends Notification
{
    use Queueable;
    public $member;
    public $user;
    public $project;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FormulatorTeamMember $member, User $user, Project $project)
    {
        $this->member = $member;
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
        return ['mail', 'database'];
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
            ->subject('Pembentukan Tim Penyusun')
            ->line('Selamat ' . $this->user->name . ' anda telah ditugaskan sebagai ' . $this->member->position . 'dalam penyusunan dokumen lingkungan hidup untuk rencana usaha dan/atau kegiatan ' . $this->project->project_title);
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
            'createdTeamMember' => $this->member,
            'user' => $notifiable,
            'message' => 'Selamat ' . $this->user->name . ' anda telah ditugaskan sebagai ' . $this->member->position . 'dalam penyusunan dokumen lingkungan hidup untuk rencana usaha dan/atau kegiatan ' . $this->project->project_title,
        ];
    }
}
