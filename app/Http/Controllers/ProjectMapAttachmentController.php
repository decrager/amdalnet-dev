<?php

namespace App\Http\Controllers;

use App\Entity\ProjectMapAttachment;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectMapAttachmentResource;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

class ProjectMapAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $files = ProjectMapAttachment::all();
        if($request->id_project){
             $files = $files->where('id_project', $request->id_project);
             return ProjectMapAttachmentResource::collection($files);
        }
        return ProjectMapAttachmentResource::collection($files);
        //
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
    }

   /**
     * Upload Maps.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(Request $request)
    {


        $file = $request->file('file');

        $map = ProjectMapAttachment::where('id_project', $request->input('id_project'))
           ->where('attachment_type', $request->input('attachment_type'))
           ->where('file_type', $request->input('file_type'))->first();

        if(!$map) {
            $map = new ProjectMapAttachment();
        } else {
            unlink(storage_path().DIRECTORY_SEPARATOR.$map->stored_filename);
        }

        $map->id_project = $request->input('id_project');
        $map->attachment_type = $request->input('attachment_type');
        $map->file_type = $request->input('file_type');
        $map->original_filename = $file->getClientOriginalName();
        $map->stored_filename = time().'_'.$map->id_project.'_'.uniqid('projectmap').'.'.strtolower($map->file_type);

        if($file->move(storage_path(),$map->stored_filename)){
            $map->save();

        }else {
            return [
                'message' => 'failed!'.storage_path(),
            ];
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ProjectMapAttachment  $projectMapAttachment
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectMapAttachment $projectMapAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectMapAttachment  $projectMapAttachment
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectMapAttachment $projectMapAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectMapAttachment  $projectMapAttachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectMapAttachment $projectMapAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectMapAttachment  $projectMapAttachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectMapAttachment $projectMapAttachment)
    {
        //
    }
}
