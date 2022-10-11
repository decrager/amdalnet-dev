<?php

namespace App\Http\Controllers;

use App\EntityHome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use App\Utils\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Utils\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo Storage::disk('s3')->url('public/test/61dc1555d7782.docx');
        echo '<br/>';
        echo Storage::disk('openstack')->temporaryUrl('public/test/61dc1555d7782.docx', Carbon::now()->addMinutes(30));
        echo '<br/>';
        echo Storage::temporaryUrl('amdal.png', Carbon::now()->addMinutes(30));
        echo '<br/>';
        echo Storage::temporaryUrl('public/test/61dc1555d7782.docx', Carbon::now()->addMinutes(30));
        var_dump(Storage::url('public/test/61dc1555d7782.docx'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $file = $request->file('file');
        // var_dump($file);
        $name = 'test/' . uniqid() . '.' . $file->extension();
        $file->storePubliclyAs('public', $name);
        echo Storage::url($name);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EntityHome  $entityHome
     * @return \Illuminate\Http\Response
     */
    public function show(EntityHome $entityHome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EntityHome  $entityHome
     * @return \Illuminate\Http\Response
     */
    public function edit(EntityHome $entityHome)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EntityHome  $entityHome
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntityHome $entityHome)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EntityHome  $entityHome
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntityHome $entityHome)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function s3url()
    {
        echo Storage::temporaryUrl('public/test/61dc1555d7782.docx', now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')));
        var_dump(Storage::url('public/test/61dc1555d7782.docx'));
        // var_dump(Storage::url(''));
        var_dump(Storage::disk('public')->exists('workspace'));
        var_dump(Storage::disk('public')->makeDirectory('uji-kelayakan'));

        var_dump('tes', Storage::lastmodified('public/test/62be3b1e3cb5b111.pdf'));
        var_dump(Storage::directories('public/test/..'));
        // var_dump(Storage::disk('public')->directories('workspace/..'));
        var_dump(Storage::disk('public')->directories(dirname('workspace/sample.docx-hist')));
        var_dump(pathinfo('/public/workspace/basename'));
        var_dump(dirname('/public/workspace/basename'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function s3template()
    {
        $templateProcessor = new TemplateProcessor('template_andal.docx');

        $templateProcessor->setValue('pemrakarsa', '12');
        $templateProcessor->setValue('project_title_s', 'title');

        $save_file_name = 'workspace-xxx-andal' . '.docx';

        $tmpName = $templateProcessor->save();
        Storage::disk('public')->put('workspace/' . $save_file_name, file_get_contents($tmpName));
        unlink($tmpName);

        return redirect()->away(Storage::disk('public')->temporaryUrl('workspace/' . $save_file_name, now()->addMinutes(30)));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function s3topdf()
    {
        $save_file_name = 'workspace-xxx-andal' . '.docx';
        $export_file_name = 'workspace-xxx-andal' . '.pdf';

        $domPdfPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');

        $tmpName = tempnam(sys_get_temp_dir(),'');
        $tmpFile = Storage::disk('public')->get('workspace/' . $save_file_name);
        file_put_contents($tmpName, $tmpFile);
        $Content = IOFactory::load($tmpName);

        //Save it into PDF
        
        $PDFWriter = IOFactory::createWriter($Content, 'PDF');
        
        unlink($tmpName);

        $tmpName = tempnam(sys_get_temp_dir(),'');
        $PDFWriter->save($tmpName);
        Storage::disk('public')->put('workspace/' . $export_file_name, file_get_contents($tmpName));
        unlink($tmpName);

        return redirect()->away(Storage::disk('public')->temporaryUrl('workspace/' . $export_file_name, now()->addMinutes(env('TEMPORARY_URL_TIMEOUT'))));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function s3override()
    {
        $save_file_name = 'workspace-xxx-andal' . '.docx';
        echo Storage::disk('public')->url('workspace/' . $save_file_name);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function s3delete()
    {
        $save_file_name = 'workspace-xxx-andal' . '.docx';
        // echo Storage::disk('public')->copy('workspace/' . $save_file_name);
    }

    /**
     * Test upload
     *
     * @return \Illuminate\Http\Response
     */
    public function s3upload(Request $request)
    {
        $file = $request->file('file');

        // $name = 'test/' . uniqid() . '111.' . $file->extension();
        // $path = $file->storePubliclyAs('public', $name);
        // var_dump($path);

        $name = 'test/' . uniqid() . '222.' . $file->extension();
        $path = $file->storePubliclyAs($name, 'public');
        var_dump($path);
        
        // $name = 'test/' . uniqid() . '333.' . $file->extension();
        // $path = $file->storeAs($name, 'public');

        // $name = 'test/' . uniqid() . '444.' . $file->extension();
        // $path = $file->storeAs('public', $name);
    }

    /**
     * Test lastmodified
     *
     * @return \Illuminate\Http\Response
     */
    public function s3lastmodified()
    {
    }

    /**
     * Test lastmodified
     *
     * @return \Illuminate\Http\Response
     */
    public function s3check(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            var_dump($user);
        }
        // $files = Storage::disk('public')->directories('workspace/sample.docx-hist');
        // var_dump($files);
        var_dump(Auth::check());
    }
}
