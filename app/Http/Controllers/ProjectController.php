<?php

namespace App\Http\Controllers;

use App\Entity\Project;
use App\Http\Resources\ProjectResource;
use App\MapProject;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Shapefile\Shapefile;
use Shapefile\ShapefileReader;
use VIPSoft\Unzip\Unzip;
use Illuminate\Support\Facades\File;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->lastInput) {
            return Project::whereDoesntHave('team')->orderBy('id', 'DESC')->first();
        } else if ($request->formulatorId) {
            //this code to get project base on formulator
            return Project::select('projects.*', 'provinces.name as province', 'districts.name as district', 'initiators.name as applicant', 'users.avatar as avatar', 'formulator_teams.id as team_id')->where(function ($query) use ($request) {
                return $request->document_type ? $query->where('result_risk', $request->document_type) : '';
            })->where(
                function ($query) use ($request) {
                    return $request->id_prov ? $query->where('projects.id_prov', $request->id_prov) : '';
                }
            )->where(
                function ($query) use ($request) {
                    return $request->id_district ? $query->where('projects.id_district', $request->id_district) : '';
                }
            )->where(
                function ($query) use ($request) {
                    return $request->lpjpId ? $query->where('projects.id_lpjp', $request->lpjpId) : '';
                }
            )->where(
                function ($query) use ($request) {
                    return $request->formulatorId ? $query->where('formulators.id', $request->formulatorId) : '';
                }
            )->leftJoin('provinces', 'projects.id_prov', '=', 'provinces.id')->leftJoin('initiators', 'projects.id_applicant', '=', 'initiators.id')->leftJoin('users', 'initiators.email', '=', 'users.email')->leftJoin('districts', 'projects.id_district', '=', 'districts.id')->leftJoin('formulator_teams', 'projects.id', '=', 'formulator_teams.id_project')->leftJoin('formulator_team_members', 'formulator_teams.id', '=', 'formulator_team_members.id_formulator_team')->leftJoin('formulators', 'formulators.id', '=', 'formulator_team_members.id_formulator')->orderBy('projects.id', 'DESC')->paginate($request->limit);
        }

        return Project::select('projects.*', 'provinces.name as province', 'districts.name as district', 'initiators.name as applicant', 'users.avatar as avatar', 'formulator_teams.id as team_id', 'announcements.id as announcementId')->where(function ($query) use ($request) {
            return $request->document_type ? $query->where('result_risk', $request->document_type) : '';
        })->where(
            function ($query) use ($request) {
                return $request->id_prov ? $query->where('projects.id_prov', $request->id_prov) : '';
            }
        )->where(
            function ($query) use ($request) {
                return $request->id_district ? $query->where('projects.id_district', $request->id_district) : '';
            }
        )->where(
            function ($query) use ($request) {
                return $request->lpjpId ? $query->where('projects.id_lpjp', $request->lpjpId) : '';
            }
        )->where(
            function ($query) use ($request) {
                return $request->initiatorId ? $query->where('projects.id_applicant', $request->initiatorId) : '';
            }
        )->leftJoin('provinces', 'projects.id_prov', '=', 'provinces.id')->leftJoin('initiators', 'projects.id_applicant', '=', 'initiators.id')->leftJoin('users', 'initiators.email', '=', 'users.email')->leftJoin('districts', 'projects.id_district', '=', 'districts.id')->leftJoin('formulator_teams', 'projects.id', '=', 'formulator_teams.id_project')->leftJoin('announcements', 'announcements.project_id', '=', 'projects.id')->orderBy('projects.id', 'DESC')->paginate($request->limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
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
                // 'id_formulator_team' => 'required',
                'kbli' => 'required',
                'result_risk' => 'required',
                'required_doc' => 'required',
                'type_formulator_team' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create fileKtr
            $ktrName = '';
            if ($request->file('fileKtr')) {
                $fileKtr = $request->file('fileKtr');
                $ktrName = 'project/ktr' . uniqid() . '.' . $fileKtr->extension();
                $fileKtr->storePubliclyAs('public', $ktrName);
            }

            //create file map
            $mapName = array();
            if ($files = $request->file('fileMap')) {
                foreach ($files as $file) {
                    $name = uniqid() . '.zip';
                    $mapName[] = $name;
                    $file->move(storage_path('app/public/map'), $name);
                }
            }

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
                'id_formulator_team' => isset($params['id_formulator_team']) ? $params['id_formulator_team'] : null,
                'kbli' => $params['kbli'],
                'result_risk' => $params['result_risk'],
                'required_doc' => $params['required_doc'],
                'id_project' => $params['id_project'],
                'type_formulator_team' => $params['type_formulator_team'],
                'id_lpjp' => isset($params['id_lpjp']) ? $params['id_lpjp'] : null,
                'map' => implode(',', $mapName),
                'ktr' => Storage::url($ktrName),
            ]);

            return new ProjectResource($project);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project)
    {
        return $project;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @return Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function update(Request $request, Project $project)
    {

        if ($project === null) {
            return response()->json(['error' => 'project not found'], 404);
        }

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
                // 'id_formulator_team' => 'required',
                'kbli' => 'required',
                'result_risk' => 'required',
                'required_doc' => 'required',
                'type_formulator_team' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create file map
            if ($request->file('fileMap')) {
                $file = $request->file('fileMap');
                $name = 'project/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
            } else {
                $name = null;
            }

            //create file ktr
            if ($request->file('fileKtr')) {
                $fileKtr = $request->file('fileKtr');
                $ktrName = 'project/ktr' . uniqid() . '.' . $fileKtr->extension();
                $fileKtr->storePubliclyAs('public', $ktrName);
            } else {
                $ktrName = null;
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
            $project->id_formulator_team = isset($params['id_formulator_team']) ? $params['id_formulator_team'] : null;
            $project->kbli = $params['kbli'];
            $project->result_risk = $params['result_risk'];
            $project->required_doc = $params['required_doc'];
            $project->id_project = $params['id_project'];
            $project->type_formulator_team = $params['type_formulator_team'];
            $project->id_lpjp = isset($params['id_lpjp']) ? $params['id_lpjp'] : null;
            $project->map = $name != null ? Storage::url($name) : $project->map;
            $project->ktr = $ktrName != null ? Storage::url($ktrName) : $project->ktr;

            $project->save();
        }

        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
        } catch (Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
