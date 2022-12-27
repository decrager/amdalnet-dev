<?php

namespace App\Notifications;

use App\Entity\TestingMeeting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class MeetingInvitation extends Notification
{
    use Queueable;

    public $meeting;
    public $attachment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TestingMeeting $meeting, $idProject, $attachment, $pdf, $filename, $role)
    {
        $this->meeting = $meeting;
        $this->attachment = $attachment;
        $this->id_project = $idProject;
        $this->pdf = $pdf;
        $this->filename = $filename;
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
        Carbon::setLocale('id');
        $document = '';
        $url = url("/#/project/docspace/". $this->id_project). "/". 0;
        $role = '';

        if ($this->role[0] == 'examiner-chief') {
            $role = 'Ketua Ahli';
        } else if ($this->role[0] == 'examiner-secretary') {
            $role = 'Kepala Sekretariat';
        } else if ($this->role[0] == 'examiner-administration') {
            $role = 'Validator Administrasi';
        } else if ($this->role[0] == 'examiner-substance') {
            $role = 'Validator Substansi';
        } else if ($this->role[0] == 'examiner-community') {
            $role = 'Ahli Komunitas';
        }

        if($this->meeting->document_type == 'ka') {
            $document = 'Formulir Kerangka Acuan';
        } else if($this->meeting->document_type == 'rkl-rpl') {
            $document = 'Dokumen Andal dan RKL RPL';
        }
        $link = '-';
        if ($this->meeting->link !== '-') {
            $link = "&lt;a href='{$this->meeting->link}'&gt;{$this->meeting->link}&lt;/a&gt;";
        }
        return (new MailMessage)
                    ->subject('Undangan Rapat Pembahasan ' . $this->documentType())
                    ->greeting('Yth. ' . $notifiable->name)
                    ->line('Sehubungan dengan akan diadakannya acara rapat Pemeriksan ' . $document . ' dengan nama kegiatan/usaha ' . $this->meeting->project->project_title . ', Maka kami mengundang bapak/ibu untuk hadir dalam acara tersebut yang akan dilaksanakan pada:')
                    ->line(new HtmlString('Hari/Tanggal: ' . Carbon::createFromFormat('Y-m-d', $this->meeting->meeting_date)->isoFormat('dddd') . ', ' . Carbon::createFromFormat('Y-m-d', $this->meeting->meeting_date)->isoFormat('D MMMM Y') . '<br>Waktu: ' . date('H:i', strtotime($this->meeting->meeting_time)) . ' ' . $this->meeting->zone. '<br>' . 'Tempat: ' . $this->meeting->location . '<br>' . 'Link Meeting: ' . htmlspecialchars_decode($link)))
                    ->line('Untuk memberikan Saran Masukan atau Tanggapan, silakan login ke dalam sistem Amdalnet terlebih dulu menggunakan akun yg telah diberikan.')
                    ->action('Klik Untuk Memberikan SPT', $url)
                    ->line('Demikian undangan ini kami sampaikan, mengingat pentingnya acara tersebut maka dimohon kehadiran tepat pada waktunya, Atas perhatiannya, kami ucapkan terimakasih.')
                    ->salutation(new HtmlString('Hormat kami,<br>' . Auth::user()->name . '<br>' . $role))
                    ->attach($this->attachment, [
                        'as' => 'Undangan Rapat.pdf',
                        'mime' => 'application/pdf'
                    ])
                    ->attach($this->pdf, [
                        'as' => $this->filename.'.pdf',
                        'mime' => 'application/pdf'
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        Carbon::setLocale('id');
        $document = '';
        if($this->meeting->document_type == 'ka') {
            $document = 'Formulir Kerangka Acuan';
        } else if($this->meeting->document_type == 'rkl-rpl') {
            $document = 'Dokumen Andal dan RKL RPL';
        }
        if(!$notifiable->roles->contains('name', 'formulator')) {
            $message = 'Sehubungan dengan akan diadakannya acara rapat Pemeriksan ' . $document . ' dengan nama kegiatan/usaha ' . $this->meeting->project->project_title . ', Maka kami mengundang bapak/ibu untuk hadir dalam acara tersebut yang akan dilaksanakan pada: ';
            $message .= 'Hari/Tanggal: ' . Carbon::createFromFormat('Y-m-d', $this->meeting->meeting_date)->isoFormat('dddd') . ', ' . Carbon::createFromFormat('Y-m-d', $this->meeting->meeting_date)->isoFormat('D MMMM Y') . ', ';
            $message .= 'Waktu: ' . $this->meeting->meeting_time . ', ';
            $message .= 'Tempat: ' . $this->meeting->location . '. ';
            $message .= 'Demikian undangan ini kami sampaikan, mengingat pentingnya acara tersebut maka dimohon kehadiran tepat pada waktunya, Atas perhatiannya, kami ucapkan terimakasih. ';
            $message .= 'Hormat kami, ' . Auth::user()->name;

            return [
                'meeting' => $this->meeting ,
                'member' => $notifiable,
                'message' => $message,
                'path' => '#',
            ];
        } else {
            return [
                'meeting' => $this->meeting ,
                'member' => $notifiable,
                'message' => 'Kegiatan ' . $this->meeting->project->project_title . ' akan dilakukan pembahasan ' . $this->documentType() . ' oleh Tim Uji Kelayakan',
                'path' => '#',
            ];
        }
    }

    private function documentType()
    {
        $document_type = '';
        if($this->meeting->document_type == 'ka') {
            $document_type = 'Kerangka Acuan';
        } else if($this->meeting->document_type == 'rkl-rpl') {
            $document_type = 'Analisis Dampak Lingkungan dan Rencana Pengelolaan Hidup dan Rencana Pemantauan Lingkungan Hidup';
        } else if($this->meeting->document_type == 'ukl-upl') {
            $document_type = 'Upaya Pengelolaan Lingkungan Hidup dan Upaya Pemantauan Lingkungan Hidup';
        }

        return $document_type;
    }
}
