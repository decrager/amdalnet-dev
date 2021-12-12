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

class WordTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test regex docx';

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
        $xmlBlock = null;
        $blockname = 'anggota_tim';
        $data = file_get_contents('tes.txt'); 

        $matches = array();
        preg_match(
            '/(.*((?s)<w:p\b(?:(?!<w:p\b).)*?\${' . $blockname . '}<\/w:.*?p>))(.*)((?s)<w:p\b(?:(?!<w:p\b).)[^$]*?\${\/' . $blockname . '}<\/w:.*?p>)/isx',
            $data,
            $matches
        );
        var_dump('cloneblock', $matches);
    }
}
