<?php

namespace App\Http\Controllers;

use App\Entity\Project;
use App\Entity\ProjectPkplhFinal;
use App\Services\OssService;
use Illuminate\Http\Request;

class PkplhFinalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->id_project) {
            $pkplhFinal = ProjectPkplhFinal::where('id_project', $request->id_project)->first();
            if ($pkplhFinal) {
                return [$pkplhFinal];
            }
            return [];
        }
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
        $data = $request->all();
        if($request->hasFile('pkplh_final')) {
            $project = Project::findOrFail($data['id_project']);
            //create file
            $file_name = $project->project_title .' - ' . $project->initiator->name . ' - final';
            $file = $request->file('pkplh_final');
            $name = 'pkplh/' . $file_name . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            //create environmental expert  
            $pkplh = ProjectPkplhFinal::where('id_project', $data['id_project'])->first();
            $sendLicenseStatus = false;

            if(!$pkplh) {
                $sendLicenseStatus = true;
                $pkplh = new ProjectPkplhFinal();
                $pkplh->id_project = $data['id_project'];
                $pkplh->number = $data['number'];
                $pkplh->title = $data['title'];
                $pkplh->date_published = $data['date_published'];
            }

            $pkplh->file = $name;
            $saved = $pkplh->save();

            if ($saved) {
                if ($sendLicenseStatus) {
                    OssService::receiveLicenseStatus($project, '50');
                }
                return response()->json(['code' => 200, 'data' => $pkplh]);
            }
            return response()->json(['code' => 500]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ProjectPkplhFinal  $projectPkplhFinal
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectPkplhFinal $projectPkplhFinal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectPkplhFinal  $projectPkplhFinal
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectPkplhFinal $projectPkplhFinal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectPkplhFinal  $projectPkplhFinal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectPkplhFinal $projectPkplhFinal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectPkplhFinal  $projectPkplhFinal
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectPkplhFinal $projectPkplhFinal)
    {
        //
    }
}
