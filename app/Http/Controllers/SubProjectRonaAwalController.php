<?php

namespace App\Http\Controllers;

use App\Entity\ComponentType;
use App\Entity\SubProjectRonaAwal;
use App\Http\Resources\SubProjectRonaAwalResource;
use Illuminate\Http\Request;

class SubProjectRonaAwalController extends Controller
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
            $rona_awals = SubProjectRonaAwal::select('sub_project_rona_awals.*',
                'rona_awal.name AS name_master',
                'rona_awal.id_component_type AS id_component_type_master',
                'p.id AS id_project')
                ->leftJoin('rona_awal', 'sub_project_rona_awals.id_rona_awal', '=', 'rona_awal.id')
                ->leftJoin('sub_project_components AS spc', 'sub_project_rona_awals.id_sub_project_component', '=', 'spc.id')
                ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
                ->leftJoin('projects AS p', 'sp.id_project', '=', 'p.id')
                ->where('p.id', $params['id_project'])
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
                return SubProjectRonaAwalResource::collection($rona_awals);
            }
        } else {
            return SubProjectRonaAwalResource::collection(SubProjectRonaAwal::with('rona_awal')->get());
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function show(SubProjectRonaAwal $subProjectRonaAwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function edit(SubProjectRonaAwal $subProjectRonaAwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubProjectRonaAwal $subProjectRonaAwal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubProjectRonaAwal $subProjectRonaAwal)
    {
        try {
            $subProjectRonaAwal->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
