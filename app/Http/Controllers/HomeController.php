<?php

namespace App\Http\Controllers;

use App\EntityHome;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
use App\Utils\Storage;
use Carbon\Carbon;
use App\Utils\TemplateProcessor;

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
        echo Storage::temporaryUrl('public/test/61dc1555d7782.docx', Carbon::now()->addMinutes(30));
        var_dump(Storage::url('public/test/61dc1555d7782.docx'));
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
        Storage::disk('public')->put('workspace/' . $save_file_name, file_get_contents($tmpName), 'public');
        unlink($tmpName);

        echo Storage::disk('public')->url('workspace/' . $save_file_name);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function s3download()
    {
        
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
}
