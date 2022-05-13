<?php

namespace App\Notifications;

use App\Entity\FeasibilityTestTeam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TUKAssigned extends Notification
{
    use Queueable;

    public $feasibilityTestTeam;
    public $position;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FeasibilityTestTeam $feasibilityTestTeam, $position)
    {
        $this->feasibilityTestTeam = $feasibilityTestTeam;
        $this->position = $position;
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
                    ->subject($this->getTeamName())
                    ->line('Anda baru saja dimasukkan ke ' . $this->getTeamName() . ' sebagai ' . $this->position);
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
            'feasibilityTestTeam' => $this->feasibilityTestTeam,
            'member' => $notifiable,
            'message' => 'Anda baru saja dimasukkan ke '.$this->getTeamName() . ' sebagai ' . $this->position,
            'path' => '/project',
        ];
    }

    private function getTeamName() {
        $team_name = '';
        if($this->feasibilityTestTeam->authority == 'Pusat') {
            $team_name = 'Tim Uji Kelayakan Pusat ' . $this->feasibilityTestTeam->team_number;
        } else if($this->feasibilityTestTeam->authority == 'Provinsi' && $this->feasibilityTestTeam->provinceAuthority) {
            $team_name = 'Tim Uji kelayakan Provinsi ' . ucwords(strtolower($this->feasibilityTestTeam->provinceAuthority->name)) . ' ' . $this->feasibilityTestTeam->team_number;
        } else if($this->feasibilityTestTeam->authority == 'Kabupaten/Kota' && $this->feasibilityTestTeam->districtAuthority) {
            $team_name = 'Tim Uji kelayakan ' . ucwords(strtolower($this->feasibilityTestTeam->districtAuthority->name)) . ' ' . $this->feasibilityTestTeam->team_number;
        }

        return $team_name;
    }
}
