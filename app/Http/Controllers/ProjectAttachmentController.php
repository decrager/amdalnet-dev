<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entity\Project;

class ProjectAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::where('id', $id)->first();
        if (!$project){
            return response('Kegiatan tidak ditemukan.', 404);
        }
        $result = null;
        switch ($project->required_doc){
            case 'AMDAL':
                $result = $this->getAmdalAttachments($project);
                break;
            case 'UKL-UPL':
                $result = $this->getUKLUPLAttachments($project);
                break;
        }
        return response()->json($project, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    private function getAmdalAttachments($project){
        return $project;
    }
    private function getUKLUPLAttachments($project){
        return $project;
    }
}
