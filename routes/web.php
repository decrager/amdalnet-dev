<?php

use App\Entity\Initiator;
use App\Entity\LukMember;
use App\Entity\OssNib;
use App\Entity\Project;
use App\Entity\SubProject;
use App\Entity\WorkspaceComment;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportDocument;
use App\Http\Controllers\LaravueController;
use App\Http\Controllers\WebgisController;
use App\Utils\TemplateProcessor;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('doc-uklupl/{id}', [ExportDocument::class, 'ExportUklUpl']);
Route::get('berita-acara/{id}/{type}', [ExportDocument::class, 'ExportBA']);
//Route::get('form-ka/{id}/pdf', [ExportDocument::class, 'ExportKA']);
Route::post('upload-map', [WebgisController::class, 'store']);
// Route::get('project-map', [ProjectMapAttachmentController::class, 'index']);

Route::group(['middleware' => 'web'], function () {
    Route::get(env('LARAVUE_PATH'), [LaravueController::class, 'index'])->where('any', '.*')->name('laravue');
});

Route::get('auth/new-password',  [AuthController::class, 'resetPasswordRedirect'])->name('password.reset');
Route::get('#/oss/new-password',  [AuthController::class, 'resetPasswordRedirect'])->name('password.reset.redirect');

Route::get('/test', function () {
    $dataProject = Project::first();
    $dataDate = (string)$dataProject->updated_at->format('d-m-Y');
    dd($dataDate);


    ////

    $document = public_path('document/template_rekap_komentar.docx');
    $commentsRaw = WorkspaceComment::with('user')->where('document_type', 'ukl-upl')->where('id_project', '1910')->get()->toArray();
    // dd($commentsRaw);
    $commentsContent = $commentsRaw[0]['suggest'];
    dd($commentsContent);
    // $commentsContent = '<p style="background-color:#FFFF00;color:#FF0000;">Some text</p>';
    $phpWord = new PhpWord();
    $section = $phpWord->addSection();
    // $section = (new PhpWord())->addSection();
    Html::addHtml($section, $commentsContent);
    // $containers = $section->getElements();
    // dd($section);
    $phpWord->save($document, 'Word2007');

    // $comments = WorkspaceComment::where('document_type', 'ukl-upl')->where('id_project', '1846')->get();
    // $results = $comments->toArray();
    // // dd($results);
    // $document = new TemplateProcessor(public_path('document/template_rekap_komentar.docx'));
    // $project = Project::findOrFail(720)->first();
    // $document->setValue('nama_kegiatan', $project->project_title);
    // $dataRekap = [
    //     [
    //         'nama' => 'Agung',
    //         'posisi' => 'Ahli udara',
    //         'spt' => 'sudah bagus',
    //         'halaman_spt' => 1,
    //         'perbaikan_pemrakarsa' => 'oke banget',
    //         'halaman_perbaikan' => 2,
    //         'kategori_komentar' => 'pakar',
    //     ],
    //     [
    //         'nama' => 'Gunawan',
    //         'posisi' => 'Ahli Api',
    //         'spt' => 'sudah sangat bagus',
    //         'halaman_spt' => 10,
    //         'perbaikan_pemrakarsa' => 'oke deh',
    //         'halaman_perbaikan' => 6,
    //         'kategori_komentar' => 'pakar',
    //     ],
    //     [
    //         'nama' => 'Gun',
    //         'posisi' => 'Ahli Tanah',
    //         'spt' => 'sudah sudah',
    //         'halaman_spt' => 4,
    //         'perbaikan_pemrakarsa' => 'oke oke banget',
    //         'halaman_perbaikan' => 9,
    //         'kategori_komentar' => 'daerah',
    //     ],
    //     [
    //         'nama' => 'husni',
    //         'posisi' => 'Ahli Air',
    //         'spt' => 'sudah',
    //         'halaman_spt' => 99,
    //         'perbaikan_pemrakarsa' => 'oke sih',
    //         'halaman_perbaikan' => 104,
    //         'kategori_komentar' => 'pakar',
    //     ],
    //     [
    //         'nama' => 'Husni',
    //         'posisi' => 'Ahli Air',
    //         'spt' => 'sudah bagus banget',
    //         'halaman_spt' => 5,
    //         'perbaikan_pemrakarsa' => 'oke juga',
    //         'halaman_perbaikan' => 6,
    //         'kategori_komentar' => 'pakar',
    //     ],
    //     [
    //         'nama' => 'Agung',
    //         'posisi' => 'Ahli udara',
    //         'spt' => 'sudah oke banget sih',
    //         'halaman_spt' => 10,
    //         'perbaikan_pemrakarsa' => 'oke oke aja',
    //         'halaman_perbaikan' => 11,
    //         'kategori_komentar' => 'pakar',
    //     ],
    //     [
    //         'nama' => 'Guna',
    //         'posisi' => 'Ahli abu',
    //         'spt' => 'sudah bagus oke',
    //         'halaman_spt' => 111,
    //         'perbaikan_pemrakarsa' => 'oke juga oke',
    //         'halaman_perbaikan' => 110,
    //         'kategori_komentar' => 'daerah',
    //     ],
    //     [
    //         'nama' => 'gunan',
    //         'posisi' => 'Ahli komputer',
    //         'spt' => 'sudah bagus',
    //         'halaman_spt' => 111,
    //         'perbaikan_pemrakarsa' => 'oke banget',
    //         'halaman_perbaikan' => 120,
    //         'kategori_komentar' => 'pusat',
    //     ],
    // ];

    // $result = collect($dataRekap)->groupBy('kategori_komentar');
    // $i = 0;
    // foreach ($result as $kategory_name => $kategory) {
    //     $replacements[] = array(
    //         'kategori_komentar' => $kategory_name,
    //         'no_ahli' => '${no_ahli_'.$i.'}',
    //         'nama_tuk_ahli' => '${nama_tuk_ahli_'.$i.'}',
    //         'position_ahli' => '${position_ahli_'.$i.'}',
    //         'saran_pendapat_ahli' => '${saran_pendapat_ahli_'.$i.'}',
    //         'halaman_spt_ahli' => '${halaman_spt_ahli_'.$i.'}',
    //         'perbaikan_pemrakarsa_ahli' => '${perbaikan_pemrakarsa_ahli_'.$i.'}',
    //         'halaman_perbaikan_ahli' => '${halaman_perbaikan_ahli_'.$i.'}',
    //     );
    //     $i++;
    // }
    // $document->cloneBlock('block_komentar', count($replacements), true, false, $replacements);
    // $i = 0;
    // foreach($result as $group) {
    //     // dd($group);
    //     $values = array();
    //     foreach($group as $key => $row) {
    //         dd($row);
    //         $values[] = array(
    //             "no_ahli_{$i}" => $key + 1,
    //             "nama_tuk_ahli_{$i}" => $row['nama'],
    //             "position_ahli_{$i}" => $row['posisi'],
    //             "saran_pendapat_ahli_{$i}" => $row['spt'],
    //             "halaman_spt_ahli_{$i}" => $row['halaman_spt'],
    //             "perbaikan_pemrakarsa_ahli_{$i}" => $row['perbaikan_pemrakarsa'],
    //             "halaman_perbaikan_ahli_{$i}" => $row['halaman_perbaikan'],
    //         );
    //     }
    //     $document->cloneRowAndSetValues("no_ahli_{$i}", $values);
    //     $i++;
    // }
    // $outputWord = storage_path('app/public/' . 'print-rekap-komentar.docx');
    // $document->saveAs($outputWord);

    // return Response()->download($outputWord);
});


Route::get('/test1', function () {
    $project = Project::findOrFail('2056');
    $initiator = Initiator::find($project->id_applicant);
    if (!$initiator) {
        Log::error('Initiator not found');
        return false;
    }
    $ossNib = OssNib::where('nib', $initiator->nib)->first();
    if (!$ossNib) {
        Log::error('OSSNib not found');
        return false;
    }
    $jsonContent = $ossNib->json_content;
    $subProjects = $jsonContent['data_proyek'];
    $subProjectsAmdalnet = SubProject::where('id_project', $project->id)->get();
    if (empty($subProjectsAmdalnet)) {
        Log::error('Sub projects not found');
        return false;
    }
    $subProjectsAmdalnetIdProyeks = [];
    foreach ($subProjectsAmdalnet as $sp) {
        array_push($subProjectsAmdalnetIdProyeks, $sp->id_proyek);
    }
    return [
        'ossNib' => $ossNib,
        'initiator' => $initiator,
        'subProjects' => $subProjects,
        'subProjectsAmdalnetIdProyeks' => $subProjectsAmdalnetIdProyeks,
    ];
});

Route::get('/test2', function () {
    $headers = array(
        "Content-type"=>"text/html",
        "Content-Disposition"=>"attachment;Filename=myGeneratefile.doc"
    );
    $commentsRaw = WorkspaceComment::with('user')->where('document_type', 'ukl-upl')->where('id_project', '1910')->get()->toArray();
    // dd($commentsRaw);
    $commentsContent = $commentsRaw[0]['suggest'];
    // dd($commentsContent);
    $content = '<html>
            <head><meta charset="utf-8"></head>
            <body>
                <p>My Blog Laravel 7 generate word document from html Example - Nicesnippets.com</p>
                <ul><li>Php</li><li>Laravel</li><li>Html</li></ul>
            </body>
            </html>';
    $content1 = '<html>
    <head><meta charset="utf-8"></head>
    <body>';
    $content2 = '</body>
    </html>';
    $content3 = '
        <p style="margin: 0pt 0pt 0pt 36pt; text-align: center; text-indent: -36pt; line-height: 115%; font-size: 12pt; font-family: Cambria, serif;"><span style="color: rgb(52, 73, 94);"><strong><span style="font-size: 10.0pt; line-height: 115%; font-family: Tahoma, sans-serif;">KOMPILASI SARAN/PENDAPAT/TANGGAPAN TERTULIS PESERTA RAPAT TIM TEKNIS </span></strong></span></p>
        <p style="text-align: center; line-height: 115%; margin: 0pt; font-size: 12pt; font-family: Cambria, serif;"><strong><span style="font-size: 10pt; line-height: 115%; font-family: Tahoma, sans-serif; color: rgb(52, 73, 94);">KOMISI PENILAI AMDAL PUSAT &ndash; KEMENTERIAN LINGKUNGAN HIDUP DAN KEHUTANAN <br></span><span style="font-size: 10.0pt; font-family: Tahoma, sans-serif;"><span style="color: rgb(52, 73, 94);">PEMBAHASAN</span> </span> </strong></p>
        <p style="text-align: center; line-height: 115%; margin: 0pt; font-size: 12pt; font-family: Cambria, serif;">&nbsp;</p>
        <p style="text-align: center; line-height: 115%; margin: 0pt; font-size: 12pt; font-family: Cambria, serif;">&nbsp;</p>
    ';
    return \Response::make($content1.$content3.$content2,200, $headers);
});
