/Work/laravel/amdalnet/app/Http/Controllers/AndalComposingController.php
2325: $templateProcessor->saveAs(Storage::disk('public')->path('workspace/' . $save_file_name));
2697: $templateProcessor->saveAs(Storage::disk('public')->path('formulir/' . $save_file_name));
2699: $templateProcessor->saveAs(Storage::disk('public')->path('workspace/' . $save_file_name));

fix:
$tmpName = $templateProcessor->save();
Storage::disk('public')->put('workspace/' . $save_file_name, file_get_contents($tmpName));
unlink($tmpName);


/Work/laravel/amdalnet/app/Http/Controllers/ExportDocument.php
228: $Content = IOFactory::load(storage_path('app/public/formulir/' . $getProject->attachment));
fix:
$tmpName = tempnam();
$tmpFile = Storage::disk('public')->get('formulir/' . $getProject->attachment);
file_put_contents($tmpName, $tmpFile);
$Content = IOFactory::load($tmpName);
...
unlink($tmpName);

234: $PDFWriter->save(storage_path('app/public/formulir/' . $getName[0]) . '.pdf');
fix:
$tmpName = tempnam();
$PDFWriter->save($tmpName);
Storage::disk('public')->put('formulir/' . $getName[0]) . '.pdf', file_get_contents($tmpName));
unlink($tmpName);

236: return response()->download(storage_path('app/public/formulir/' . $getName[0] . '.pdf'))->deleteFileAfterSend(false);

383: $templateProcessor->saveAs($save_file_name);
384: return response()->download($save_file_name)->deleteFileAfterSend(false);

414:    if (!File::exists(storage_path('app/public/berita-acara/'))) {
            File::makeDirectory(storage_path('app/public/berita-acara/'));
            $templateProcessor->saveAs(storage_path('app/public/berita-acara/' . $save_file_name));
        }

        return response()->download(storage_path('app/public/berita-acara/' . $save_file_name))->deleteFileAfterSend(false);

428:    $dokumenKa->storePubliclyAs('public/formulir/', $docName);


468:    if (File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
            return $save_file_name;
        }

740:  $templateProcessor->saveAs(storage_path('app/public/workspace/' . $save_file_name));

753: $Content = IOFactory::load(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

758:    $PDFWriter->save(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.pdf'));

        return response()->download(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.pdf'))->deleteFileAfterSend(false);


/Work/laravel/amdalnet/app/Http/Controllers/FeasibilityTestController.php
383: $templateProcessor->saveAs(Storage::disk('public')->path('uji-kelayakan/' . $save_file_name));

ref:
        $pdf = PDF::loadView('document.template_kelayakan', 
            compact(
                'project',
                'project_address',
                'docs_date',
                'tuk'
            ));

        return $pdf->download('hehey.pdf');


/Work/laravel/amdalnet/app/Http/Controllers/KaCommentController.php
363:    $templateProcessor->saveAs(Storage::disk('public')->path('recap/' . $save_file_name));
        return response()->download($save_file_name)->deleteFileAfterSend(true);


/Work/laravel/amdalnet/app/Http/Controllers/MatriksRKLController.php
252: $templateProcessor->saveAs(Storage::disk('public')->path('workspace/' . $save_file_name));

OK 342: $file->storePubliclyAs('public', $name);

/Work/laravel/amdalnet/app/Http/Controllers/MeetingReportController.php
ref:
129: Storage::disk('public')->put($name, $file['file']);

/Work/laravel/amdalnet/app/Http/Controllers/MeetReportRKLRPLController.php

/Work/laravel/amdalnet/app/Http/Controllers/ProjectMapAttachmentController.php
66:     if (file_exists(storage_path() . DIRECTORY_SEPARATOR . $map->stored_filename)) {
            unlink(storage_path() . DIRECTORY_SEPARATOR . $map->stored_filename);
        }

/Work/laravel/amdalnet/app/Http/Controllers/SKKLController.php
326: $templateProcessor->saveAs(Storage::disk('public')->path('skkl/' . $save_file_name));

389: $templateProcessor->saveAs(Storage::disk('public')->path('pkplh/' . $save_file_name));

ref:
322:        if (!Storage::disk('public')->exists('skkl')) {
                Storage::disk('public')->makeDirectory('skkl');
            }

/Work/laravel/amdalnet/app/Http/Controllers/TestingMeetingController.php

763: $templateProcessor->saveAs(Storage::disk('public')->path('adm/berkas-adm-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

928: $templateProcessor->saveAs(Storage::disk('public')->path('meet-inv/ka-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));


/Work/laravel/amdalnet/app/Http/Controllers/TestingVerificationController.php

531: $templateProcessor->saveAs(Storage::disk('public')->path('adm-no/hasil-adm-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));


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
  AccessKey : f35f44f34e984536a128c22e1b17d1fb
  SecretKey : 78600c20f35542729a5726695b63c54a
  API       : S3v4
  Path      : auto
