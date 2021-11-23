<?php

namespace App\Http\Controllers;

use App\Entity\ComponentType;
use App\Entity\SubProject;
use App\Entity\SubProjectComponent;
use App\Entity\SubProjectRonaAwal;
use App\Http\Resources\SubProjectComponentResource;
use App\Http\Resources\SubProjectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $subprojects = SubProject::with('project')
                ->where('id_project', $request->id_project)
                ->where('type', $request->sub_project_type) // 'utama'/'pendukung'
                ->get();
            return SubProjectResource::collection($subprojects);
        }
        if ($request->id_sub_project && $request->id_project_stage) {
            // return sub_project_components filter by id_sub_project & id_project_stage
            $id_project_stage = $request->id_project_stage;
            $id_sub_project = $request->id_sub_project;
            $components_by_stage = SubProjectComponent::with(['component' => function($q) use ($id_project_stage) {
                    $q->where('id_project_stage', $id_project_stage);
                }])->where('id_sub_project', $id_sub_project)
                ->where('id_project_stage', $id_project_stage)
                ->get();
            $all_stages = SubProjectComponent::with(['component' => function($q) use ($id_project_stage) {
                $q->where('id_project_stage', $id_project_stage);
                }])->where('id_sub_project', $id_sub_project)->get();
                
            $components = [];
            $ids = [];
            foreach ($components_by_stage as $component) {
                array_push($components, $component);
                array_push($ids, $component->id);
            }
            foreach ($all_stages as $component) {
                if ($component->component != null
                    || ($component->name != null && $component->id_project_stage == $id_project_stage)) {
                    if (!in_array($component->id, $ids)) {
                        array_push($components, $component);
                    }                    
                }
            }
            if ($request->sub_project_components) {
                return SubProjectComponentResource::collection($components);
            }
            if ($request->sub_project_rona_awals) {
                $rona_awals = [];
                $component_types = ComponentType::select('*')->orderBy('id', 'asc')->get();
                foreach ($component_types as $ctype) {
                    $r = [];
                    foreach ($components as $component) {
                        $id_sub_project_component = $component->id;
                        $sp_rona_awals = SubProjectRonaAwal::with(['ronaAwal', 'componentType'])
                            ->where('id_sub_project_component', $id_sub_project_component)->get();
                            foreach ($sp_rona_awals as $rona_awal) {
                                if ($ctype->id == $rona_awal->id_component_type) {
                                    array_push($r,  $rona_awal);
                                }
                                if ($rona_awal->ronaAwal != null) {
                                    if ($ctype->id == $rona_awal->ronaAwal->id_component_type) {
                                        array_push($r,  $rona_awal);
                                    }
                                }
                            }
                    }
                    array_push($rona_awals, [
                        'component_type' => $ctype,
                        'rona_awals' => $r,
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
        if ($request->component) {
            $params = $request->all();
            $component = $params['component'];
            DB::beginTransaction();
            if (SubProjectComponent::create($component)) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } else if ($request->rona_awal) {
            $params = $request->all();
            $rona_awal = $params['rona_awal'];
            DB::beginTransaction();
            if (SubProjectRonaAwal::create($rona_awal)) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }
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
