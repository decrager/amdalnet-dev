<?php

namespace App\Http\Controllers;

use App\Entity\Project;
use App\Entity\SupportDoc;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ProjectResource::Collection(Project::where(function ($query) use ($request) {
            return $request->document_type ? $query->where('result_risk', $request->document_type) : '';
        })->where(
            function ($query) use ($request) {
                return $request->id_prov ? $query->where('id_prov', $request->id_prov) : '';
            }
        )->where(
            function ($query) use ($request) {
                return $request->id_district ? $query->where('id_district', $request->id_district) : '';
            }
        )->orderBy('id', 'DESC')->paginate($request->limit));
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
        //validate request

        $validator = Validator::make(
            $request->all(),
            [
                'biz_type' => 'required',
                'project_title' => 'required',
                'scale' => 'required',
                'scale_unit' => 'required',
                'project_type' => 'required',
                'sector' => 'required',
                'description' => 'required',
                'id_applicant' => 'required',
                'id_prov' => 'required',
                'id_district' => 'required',
                'address' => 'required',
                'field' => 'required',
                'location_desc' => 'required',
                'risk_level' => 'required',
                'project_year' => 'required',
                'id_drafting_team' => 'required',
                'kbli' => 'required',
                'result_risk' => 'required',
                'required_doc' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create file map
            $file = $request->file('fileMap');
            $name = 'project/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            //create lpjp
            $project = Project::create([
                'biz_type' => $params['biz_type'],
                'project_title' => $params['project_title'],
                'scale' => $params['scale'],
                'scale_unit' => $params['scale_unit'],
                'project_type' => $params['project_type'],
                'sector' => $params['sector'],
                'description' => $params['description'],
                'id_applicant' => $params['id_applicant'],
                'id_prov' => $params['id_prov'],
                'id_district' => $params['id_district'],
                'address' => $params['address'],
                'field' => $params['field'],
                'location_desc' => $params['location_desc'],
                'risk_level' => $params['risk_level'],
                'project_year' => $params['project_year'],
                'id_formulator_team' => $params['id_drafting_team'],
                'kbli' => $params['kbli'],
                'result_risk' => $params['result_risk'],
                'required_doc' => $params['required_doc'],
                'id_project' => $params['id_project'],
                'map' => Storage::url($name),
            ]);

            return new ProjectResource($project);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return $project;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //validate request

        $validator = Validator::make(
            $request->all(),
            [
                'biz_type' => 'required',
                'project_title' => 'required',
                'scale' => 'required',
                'scale_unit' => 'required',
                'project_type' => 'required',
                'sector' => 'required',
                'description' => 'required',
                'id_applicant' => 'required',
                'id_prov' => 'required',
                'id_district' => 'required',
                'address' => 'required',
                'field' => 'required',
                'location_desc' => 'required',
                'risk_level' => 'required',
                'project_year' => 'required',
                'id_drafting_team' => 'required',
                'kbli' => 'required',
                'result_risk' => 'required',
                'required_doc' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create file map
            if($request->file('fileMap')) {
                $file = $request->file('fileMap');
                $name = 'project/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
            } else {
                $name = null;
            }

            //Update Project
            $project->biz_type = $params['biz_type'];
            $project->project_title = $params['project_title'];
            $project->scale = $params['scale'];
            $project->scale_unit = $params['scale_unit'];
            $project->project_type = $params['project_type'];
            $project->sector = $params['sector'];
            $project->description = $params['description'];
            $project->id_applicant = $params['id_applicant'];
            $project->id_prov = $params['id_prov'];
            $project->id_district = $params['id_district'];
            $project->address = $params['address'];
            $project->field = $params['field'];
            $project->location_desc = $params['location_desc'];
            $project->risk_level = $params['risk_level'];
            $project->project_year = $params['project_year'];
            $project->id_formulator_team = $params['id_drafting_team'];
            $project->kbli = $params['kbli'];
            $project->result_risk = $params['result_risk'];
            $project->required_doc = $params['required_doc'];
            $project->id_project = $params['id_project'];
            $project->map = $name != null ? Storage::url($name) : $params['map'];

            $project->save();
        }

        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
