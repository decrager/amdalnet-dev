<?php

namespace App\Http\Controllers;

use App\Entity\SubProject;
use App\Entity\SubProjectComponent;
use App\Entity\SubProjectRonaAwal;
use App\Http\Resources\SubProjectComponentResource;
use App\Http\Resources\SubProjectResource;
use Illuminate\Http\Request;

class ScopingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->id_project && $request->sub_project_type) {
            $id_project = $request->id_project;
            // list sub projects filter by type
            $subprojects = SubProject::with([
                'project' => function($q) use ($id_project) {
                    $q->select('projects.*')->where('id_project', $id_project);
                }])
                ->where('type', $request->sub_project_type) // 'utama'/'pendukung'
                ->get();
            return SubProjectResource::collection($subprojects);
        }
        if ($request->id_sub_project && $request->id_project_stage) {
            // return sub_project_components filter by id_sub_project & id_project_stage
            $id_project_stage = $request->id_project_stage;
            $id_sub_project = $request->id_sub_project;
            $components = SubProjectComponent::with(['component' => function($q) use ($id_project_stage) {
                    $q->select('components.name')->where('id_project_stage', $id_project_stage);
                }])->where('id_sub_project', $id_sub_project)
                ->where('id_project_stage', $id_project_stage)
                ->orWhereNull('id_project_stage')
                ->get();
            if ($request->sub_project_components) {
                return SubProjectComponentResource::collection($components);
            }
            if ($request->sub_project_rona_awals) {
                $rona_awals = [];
                foreach ($components as $component) {
                    $id_sub_project_component = $component->id;
                    // all component_type (no filter)
                    $rona_awals = SubProjectRonaAwal::where('id_sub_project_component', $id_sub_project_component)->get();
                    array_push($rona_awals, [
                        'component' => $component,
                        'rona_awals' => $rona_awals,
                    ]);
                }
                return [
                    'data' => $rona_awals,
                ];
            }
        }
    }

    private function getComponents($id_sub_project, $id_project_stage)
    {
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
        //
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
}
