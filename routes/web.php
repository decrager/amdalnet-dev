<?php

use App\Entity\LukMember;
use App\Entity\Project;
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
