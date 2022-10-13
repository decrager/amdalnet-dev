<?php

use App\Entity\Initiator;
use App\Entity\Project;
use App\Entity\SubProject;
use App\Http\Controllers\ExportDocument;
use App\Http\Controllers\LaravueController;
use App\Http\Controllers\WebgisController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectMapAttachmentController;
use Dompdf\Dompdf;
use PhpOffice\PhpWord\PhpWord;
use NcJoes\OfficeConverter\OfficeConverter;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

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

Route::get('/test', function() {
    // $domPdfPath = base_path('vendor/dompdf/dompdf');
    // $phpWord = new PhpWord();
    //      \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
    //      \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
    //      $Content = \PhpOffice\PhpWord\IOFactory::load(public_path('document/cetak-penapisan.docx'));
    //      $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'PDF');
    //      $PDFWriter->save(public_path('doc-pdf.pdf'));

    $document = new TemplateProcessor(storage_path('app/cetak-penapisan.docx'));
    $dataProject = Project::with('address', 'listSubProject')->findOrFail(720);
    $dataPemrakarsa = Initiator::query()->first();

    // dd($dataProject->listSubProject);

    // dd(Project::with(['listSubProject' => function($query) {$query->where('type', '!=', 'sasaas');}])->first());

    $document->setValue('nama_project', $dataProject->project_title);
    $document->setValue('no_registrasi', $dataProject->registration_no);
    $document->setValue('pemrakarsa', $dataPemrakarsa->name);
    $document->setValue('penanggung_jawab', $dataPemrakarsa->pic);
    $document->setValue('alamat_penanggung_jawab', $dataPemrakarsa->address);
    $document->setValue('nomor_telepon', $dataPemrakarsa->phone);
    $document->setValue('jabatan', $dataPemrakarsa->pic_role);
    $document->setValue('email_pemrakarsa', $dataPemrakarsa->email);
    $document->setValue('jenis_dokumen', $dataProject->required_doc);
    $document->setValue('tingkat_resiko', $dataProject->risk_level);
    $document->setValue('kewenangan', $dataProject->authority);
    $document->setValue('tanggal', now()->format('d m Y'));
    $document->setValue('jam', now()->format('H:i:s'));
    $document->setImageValue('gambar_map', public_path('images/Picture1.png'), );

    $dataDaftarKegiatan = [];
    $dataDaftarLokasi = [];
    $projectI = 1;
    $addressI = 1;
    foreach($dataProject->listSubProject as $key => $a){
        $dataDaftarKegiatan[$key]['nomor'] = ($projectI++);
        $dataDaftarKegiatan[$key]['jenis_kegiatan'] = ($a->type);
        $dataDaftarKegiatan[$key]['jenis_keg'] = (ucwords($a->type));
        $dataDaftarKegiatan[$key]['nama_kegiatan'] = ($a->name);
        $dataDaftarKegiatan[$key]['skala_besaran'] = ($a->scale);
    }


    foreach($dataProject->address as $key => $a){
        $dataDaftarLokasi[$key]['nomor_lokasi'] = ($addressI++);
        $dataDaftarLokasi[$key]['lokasi_provinsi'] = ($a->prov);
        $dataDaftarLokasi[$key]['lokasi_kota_kabupaten'] = ($a->district);
        $dataDaftarLokasi[$key]['alamat'] = ($a->address);
    }

    $document->cloneRowAndSetValues('nomor', $dataDaftarKegiatan);
    $document->cloneRowAndSetValues('nomor_lokasi', $dataDaftarLokasi);

    // dd($dataDaftarKegiatan, $dataDaftarLokasi);

    $document->saveAs(public_path('test.doc'));
    return response()->download(public_path('test.doc'));

});

Route::get('doc-uklupl/{id}', [ExportDocument::class, 'ExportUklUpl']);
Route::get('berita-acara/{id}/{type}', [ExportDocument::class, 'ExportBA']);
//Route::get('form-ka/{id}/pdf', [ExportDocument::class, 'ExportKA']);
Route::post('upload-map', [WebgisController::class, 'store']);
// Route::get('project-map', [ProjectMapAttachmentController::class, 'index']);

Route::group(['middleware' => 'web'], function () {
    Route::get(env('LARAVUE_PATH'), [LaravueController::class, 'index'])->where('any', '.*')->name('laravue');
});
