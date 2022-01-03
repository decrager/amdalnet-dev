<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Entity\MeetingReport;
use App\Entity\ExpertBankTeamMember;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class WordExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'docx export templater test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = 58;
        $type = '%';
        // $beritaAcara = MeetingReport::where([['id_project', $id], ['document_type', $type]])->first();
        // var_dump($beritaAcara);
        // $expert_bank_member = ExpertBankTeamMember::where([['id_expert_bank_team', $beritaAcara->expert_bank_team_id], ['position', 'Ketua']])->first();
        // $ketua_tuk = $expert_bank_member->expertBank->name;
        // $institution = $expert_bank_member->expertBank->institution;
        $templateProcessor = new TemplateProcessor('public/template_berita_acara.docx');

        $templateProcessor->setValue('title', 'Judul Besar');
        $templateProcessor->setValue('address', 'Alamat Institusi');
        $templateProcessor->setValue('district', 'Kecamatan district');
        $templateProcessor->setValue('province', 'Jawa Barat');
        $templateProcessor->setValue('initiator_name', 'Nama Pemrakarsa');
        $templateProcessor->setValue('date_meet', Carbon::createFromFormat('Y-m-d', '2021-11-27')->locale('id')->isoFormat('dddd/D MMMM Y'));
        $templateProcessor->setValue('location', 'Lokasi berata acara');
        $templateProcessor->setValue('initiator_name_small', 'Nama kecil');
        $templateProcessor->setValue('pic', 'Nama PIC');
        $templateProcessor->setValue('position', 'Posisi perintah');
        $templateProcessor->setValue('title_small', 'Project Title Perintah');
        $templateProcessor->setValue('address_small', 'Alamant smlal');
        $templateProcessor->setValue('district_small', 'district small ');
        $templateProcessor->setValue('province_small', 'Jawa Barat small');
        $templateProcessor->setValue('initiator_name_small', 'Nama Pemrakarsa kecil');
        $templateProcessor->setValue('ketua_tuk', 'Nama Ketua TUK');
        $templateProcessor->setValue('institution', 'Institusi');

        // block 
        $tim = [
            ['nama_anggota' => 'anggota 1'],
            ['nama_anggota' => 'anggota 2'],
            ['nama_anggota' => 'anggota 3'],
            ['nama_anggota' => 'anggota 4'],
        ];
        $templateProcessor->cloneBlock('TIM', count($tim), true, false, $tim);
        $templateProcessor->cloneBlock('anggota_tim', count($tim), true, false, $tim);
        

        $save_file_name = 'berita-acara-ka-' . uniqid() . '.docx';
        if (!File::exists(storage_path('app/public/berita-acara/'))) {
            File::makeDirectory(storage_path('app/public/berita-acara/'));
        }
        $templateProcessor->saveAs(storage_path('app/public/berita-acara/' . $save_file_name));
    }

    
}
