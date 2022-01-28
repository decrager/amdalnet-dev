<?php

namespace App\Http\Controllers;

use App\Entity\ComponentType;
use App\Entity\ProjectRonaAwal;
use App\Http\Resources\ProjectRonaAwalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectRonaAwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if (isset($params['id_project'])){
            $rona_awals = ProjectRonaAwal::select('project_rona_awals.*',
                'rona_awal.name AS name_master',
                'rona_awal.id_component_type AS id_component_type_master')
                ->leftJoin('rona_awal', 'project_rona_awals.id_rona_awal', '=', 'rona_awal.id')
                ->where('project_rona_awals.id_project', $params['id_project'])
                ->orderBy('name_master', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();
            if (isset($params['with_component_type']) && $params['with_component_type']){
                $component_types = ComponentType::select('component_types.*')
                    ->orderBy('id', 'asc')
                    ->get();                
                $data = [];
                foreach ($rona_awals as $rona_awal) {
                    $id_component_type = $rona_awal['id_component_type_master'];
                    if ($id_component_type == null) {
                        $id_component_type = $rona_awal['id_component_type'];
                    }
                    $data[$id_component_type][] = $rona_awal;
                }
                $data_output = [];
                $max_colspan = 0;
                $dummyId = 99999999;
                foreach ($component_types as $component_type) {
                    $id = $component_type['id'];
                    $count_data = 0;
                    if (array_key_exists($id, $data)) {
                        $count_data = count($data[$id]);
                    }
                    $max_colspan += $count_data;
                    array_push($data_output, [
                        'id' => $dummyId,
                        'is_component_type' => true,
                        'component_type_name' => $component_type['name'],
                    ]);
                    if (array_key_exists($id, $data)) {
                        foreach ($data[$id] as $item) {
                            $item['is_component_type'] = false;
                            array_push($data_output, $item);
                        }
                    }
                    $dummyId++;
                }
                return [
                    'data' => $data_output,
                    'colspan' => $max_colspan,
                ];
            } else {
                return ProjectRonaAwalResource::collection($rona_awals);
            }
        } else {
            return ProjectRonaAwalResource::collection(ProjectRonaAwal::with('rona_awal')->get());
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
        $all_params = $request->all();
        if (isset($all_params['rona_awals'])){
            $validator = $request->validate([
                'rona_awals' => 'required',
            ]);
            DB::beginTransaction();
            // clear items
            $first = $validator['rona_awals'][0];
            ProjectRonaAwal::where('id_project', $first['id_project'])->delete();
            $num_created = 0;
            foreach ($validator['rona_awals'] as $rona_awal){
                $rona_awal['id'] == null;
                if ($rona_awal['id_rona_awal'] > 99999999) {
                    $rona_awal['id_rona_awal'] = null;
                } else {
                    // only save id_rona_awal
                    $rona_awal['id_component_type'] = null;
                    $rona_awal['name'] = null;
                }
                if (ProjectRonaAwal::create($rona_awal)){
                    $num_created++;
                }
            }
            if ($num_created == count($validator['rona_awals'])){
                DB::commit();
                return response()->json(['code' => 200]);
            } else {
                DB::rollBack();
                return response()->json(['code' => 500]);
            }
        } else {
            $validator = $request->validate([
                'id_project' => 'required',
                'id_rona_awal' => 'required',
                'id_component_type' => 'required',
                'name' => 'required',
            ]);
            $validator['id'] = null;
            if ($validator['id_rona_awal'] != null){
                // only save id_rona_awal
                $validator['id_component_type'] = null;
                $validator['name'] = null;
            }
            DB::beginTransaction();
            if (ProjectRonaAwal::create($validator)){
                DB::commit();
                return response()->json(['code' => 200]);
            } else {
                DB::rollBack();
                return response()->json(['code' => 500]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ProjectRonaAwal  $projectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectRonaAwal $projectRonaAwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectRonaAwal  $projectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectRonaAwal $projectRonaAwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectRonaAwal  $projectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectRonaAwal $projectRonaAwal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectRonaAwal  $projectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectRonaAwal $projectRonaAwal)
    {
        //
    }
}