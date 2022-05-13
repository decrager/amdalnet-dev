<?php

namespace App\Notifications;

use App\Entity\FeasibilityTestRecap;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeasibilityTestRecapNotification extends Notification
{
    use Queueable;

    public $feasibility_test_recap;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FeasibilityTestRecap $feasibilityTestRecap)
    {
        $this->feasibility_test_recap = $feasibilityTestRecap;
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
        $is_feasib = $this->feasibility_test_recap->is_feasib ? 'layak' : 'tidak layak';
        $messsage =  $this->feasibility_test_recap->project->project_title . ' dinyatakan ' . $is_feasib;

        return [
            'feasibilityTestRecap' => $this->feasibility_test_recap,
            'user' => $notifiable,
            'message' => $messsage,
            'path' => '#',
        ];
    }
}
