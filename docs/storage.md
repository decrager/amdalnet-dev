1. Simpan ke database path dan nama file relatifnya aja
    - download dari object storage harus pakek link dicreate pakek temporaryUrl
2. Proses TemplateProcessor tidak bisa langsung menulis ke object storage, harus disimpan dulu ke tmp dir laravel / php
3. Untuk proses convert ke pdf menggunakan pdfWriter, sama seperti template processor. Keduanya menulis ke file handle, jadi harus pakek tmp dir, dan tmp file
4. Tidak perlu melakukan pengecekan ada tidaknya direktori, dan melakukan creationnya
5. Proses pertukaran data antara oods - service laravel - object storage, jadi agak kompleks
6. Fungsi Storage::disk('public')->path() tidak bisa di pakai
7. Fungsi storage_path() tidak bisa di pakai
8. Fungsi Storage::disk('something')->url diganti dengan Storage::disk('something')->temporaryUrl($path, now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')))
9. $request->file('avatar')->storeAs( masih bisa di pakai
10. storePubliclyAs( masih bisa di pakai 
11. Storage::url('') tidak bisa digunakan
12. Storage::disk('public')->makeDirectory('uji-kelayakan') tetap bisa digunakan di s3
13.



- /Work/laravel/amdalnet/app/Http/Controllers/AndalComposingController.php
OK 2325: $templateProcessor->saveAs(Storage::disk('public')->path('workspace/' . $save_file_name));
OK 2697: $templateProcessor->saveAs(Storage::disk('public')->path('formulir/' . $save_file_name));
OK 2699: $templateProcessor->saveAs(Storage::disk('public')->path('workspace/' . $save_file_name));

fix:
$tmpName = $templateProcessor->save();
Storage::disk('public')->put('workspace/' . $save_file_name, file_get_contents($tmpName));
unlink($tmpName);


- /Work/laravel/amdalnet/app/Http/Controllers/ExportDocument.php
OK 228: $Content = IOFactory::load(storage_path('app/public/formulir/' . $getProject->attachment));
fix:
$tmpName = tempnam(sys_get_temp_dir(),'');
$tmpFile = Storage::disk('public')->get('formulir/' . $getProject->attachment);
file_put_contents($tmpName, $tmpFile);
$Content = IOFactory::load($tmpName);
...
unlink($tmpName);

OK 234: $PDFWriter->save(storage_path('app/public/formulir/' . $getName[0]) . '.pdf');
fix:
$tmpName = tempnam();
$PDFWriter->save($tmpName);
Storage::disk('public')->put('formulir/' . $getName[0] . '.pdf', file_get_contents($tmpName));
unlink($tmpName);

OK 236: return response()->download(storage_path('app/public/formulir/' . $getName[0] . '.pdf'))->deleteFileAfterSend(false);
fix:
return redirect()->away(Storage::disk('public')->temporaryUrl('formulir/' . $getName[0] . '.pdf', now()->addMinutes(env('TEMPORARY_URL_TIMEOUT'))));


OK 383: $templateProcessor->saveAs($save_file_name);
OK 384: return response()->download($save_file_name)->deleteFileAfterSend(false);

OK 414:    if (!File::exists(storage_path('app/public/berita-acara/'))) {
            File::makeDirectory(storage_path('app/public/berita-acara/'));
            $templateProcessor->saveAs(storage_path('app/public/berita-acara/' . $save_file_name));
        }

        return response()->download(storage_path('app/public/berita-acara/' . $save_file_name))->deleteFileAfterSend(false);

OK 428:    $dokumenKa->storePubliclyAs('public/formulir/', $docName);


OK 468:    if (File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
            return $save_file_name;
        }
ref:
        if (Storage::disk('public')->exists('workspace' . $save_file_name)) {
            return $save_file_name;
        }
        
OK 740:  $templateProcessor->saveAs(storage_path('app/public/workspace/' . $save_file_name));

OK 753: $Content = IOFactory::load(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

OK 758:    $PDFWriter->save(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.pdf'));

        return response()->download(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.pdf'))->deleteFileAfterSend(false);


- /Work/laravel/amdalnet/app/Http/Controllers/FeasibilityTestController.php
OK 383: $templateProcessor->saveAs(Storage::disk('public')->path('uji-kelayakan/' . $save_file_name));


??? 462:        $pdf = PDF::loadView('document.template_kelayakan', 
            compact(
                'project',
                'project_address',
                'docs_date',
                'tuk'
            ));

        return $pdf->download('hehey.pdf');

- /Work/laravel/amdalnet/app/Http/Controllers/KaAttachmentController
68:     $file_name = str_replace(Storage::url(''), '', $file->file);

- /Work/laravel/amdalnet/app/Http/Controllers/KaCommentController.php
OK 369:    $templateProcessor->saveAs(Storage::disk('public')->path('recap/' . $save_file_name));
        return response()->download($save_file_name)->deleteFileAfterSend(true);

- /Work/laravel/amdalnet/app/Http/Controllers/MatriksRKLController.php
OK 253: $templateProcessor->saveAs(Storage::disk('public')->path('workspace/' . $save_file_name));

OK 342: $file->storePubliclyAs('public', $name);


- /Work/laravel/amdalnet/app/Http/Controllers/MeetingReportController.php
ref:
OK 129: Storage::disk('public')->put($name, $file['file']);

OK 623: $templateProcessor->saveAs(Storage::disk('public')->path('ba-ka/' . $save_file_name));

- /Work/laravel/amdalnet/app/Http/Controllers/MeetReportRKLRPLController.php
OK 693: $templateProcessor->saveAs(Storage::disk('public')->path('ba-ka-ukl-upl/ba-ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

OK 695: $templateProcessor->saveAs(Storage::disk('public')->path('ba-andal-rkl-rpl/ba-andal-rkl-rpl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

- /Work/laravel/amdalnet/app/Http/Controllers/ProjectMapAttachmentController.php
OK 66:     if (file_exists(storage_path() . DIRECTORY_SEPARATOR . $map->stored_filename)) {
            unlink(storage_path() . DIRECTORY_SEPARATOR . $map->stored_filename);
        }

OK 162:    $file = storage_path() . DIRECTORY_SEPARATOR . $map->stored_filename;

OK 169:    return  Response::download($file, $map->original_filename, $headers, 'attachment');

/Work/laravel/amdalnet/app/Http/Controllers/SKKLController.php
OK 326: $templateProcessor->saveAs(Storage::disk('public')->path('skkl/' . $save_file_name));

OK 389: $templateProcessor->saveAs(Storage::disk('public')->path('pkplh/' . $save_file_name));

ref:
322:        if (!Storage::disk('public')->exists('skkl')) {
                Storage::disk('public')->makeDirectory('skkl');
            }

347:        // 'file' => Storage::url('formulir/' . $idProject . '-form-ka-andal.docx'),
            'file' => Storage::disk('public')->temporaryUrl('formulir/' . $idProject . '-form-ka-andal.docx', now()->addMinutes(env('TEMPORARY_URL_TIMEOUT'))),

- /Work/laravel/amdalnet/app/Http/Controllers/TestingMeetingController.php

OK 763: $templateProcessor->saveAs(Storage::disk('public')->path('adm/berkas-adm-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

OK 928: $templateProcessor->saveAs(Storage::disk('public')->path('meet-inv/ka-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

- /Work/laravel/amdalnet/app/Http/Controllers/TestingVerificationController.php
ref:

OK 221: $peta_tapak = Storage::disk('public')->temporaryUrl('map/' . $m->stored_filename, now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')));

OK 531: $templateProcessor->saveAs(Storage::disk('public')->path('adm-no/hasil-adm-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

-----------------------
/Work/laravel/amdalnet/app/Http/Controllers/TestMeetRKLRPLController.php
796:    if($document_type == 'ukl-upl') {
            $templateProcessor->saveAs(Storage::disk('public')->path('adm/berkas-adm-uu-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));
        } else {
            $templateProcessor->saveAs(Storage::disk('public')->path('adm/berkas-adm-ar-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));
        }

960:    if($document_type == 'ukl-upl') {
            $templateProcessor->saveAs(Storage::disk('public')->path('meet-inv/ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));
        } else {
            $templateProcessor->saveAs(Storage::disk('public')->path('meet-inv/andal-rkl-rpl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));
        }

/Work/laravel/amdalnet/app/Http/Controllers/TestVerifRKLRPLController.php
636:  $templateProcessor->saveAs(Storage::disk('public')->path('adm-no/hasil-adm-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

/Work/laravel/amdalnet/app/Http/Controllers/TrackingDocumentController.php
346:    $docUklUplFilepath = storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower($project->project_title) . '.pdf');
        if ($sppl && $dpt && File::exists($docUklUplFilepath)) {

368: $skklFilepath = storage_path('app/public/skkl/' . $save_file_name);

373: if ($dokumenComplete && File::exists($skklFilepath)) {




URL       : http://s3.palapacloud.id:8080
            https://s3.palapacloud.id:8282
  AccessKey : f35f44f34e984536a128c22e1b17d1fb
  SecretKey : 78600c20f35542729a5726695b63c54a
  API       : S3v4
  Path      : auto
