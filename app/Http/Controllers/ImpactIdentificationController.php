<?php

namespace App\Http\Controllers;

use App\Entity\ImpactIdentification;
use App\Entity\ProjectRonaAwal;
use App\Http\Resources\ImpactIdentificationResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImpactIdentificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = ImpactIdentification::all();
        if ($request->id_project){
            $list = $list->where('id_project', $request->id_project);
        }
        if ($request->id_component){
            $list = $list->where('id_component', $request->id_component);
        }
        if ($request->id_rona_awal){
            $list = $list->where('id_rona_awal', $request->id_rona_awal);
        }
        if ($request->with_project_stage){
            $list = ImpactIdentification::select('impact_identifications.*',
                'pc.id_project_stage',
                'c.id_project_stage AS id_project_stage_master',
                'c.name AS component_name_master',
                'pc.name AS component_name',
                'ra.name AS rona_awal_name_master',
                'pra.name AS rona_awal_name',
                'u.name AS unit_name')
                ->leftJoin('project_components AS pc', 'impact_identifications.id_project_component', '=', 'pc.id')
                ->leftJoin('project_rona_awals AS pra', 'impact_identifications.id_project_rona_awal', '=', 'pra.id')
                ->leftJoin('units AS u', 'impact_identifications.id_unit', '=', 'u.id')
                ->leftJoin('components AS c', 'pc.id_component', '=', 'c.id')
                ->leftJoin('rona_awal AS ra', 'pra.id_rona_awal', '=', 'ra.id')
                ->where('impact_identifications.id_project', $request->id_project)
                ->get();
            return ImpactIdentificationResource::collection($list);
        }
        return ImpactIdentificationResource::collection($list);
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
        $params = $request->all();
        if (isset($params['checked']) && isset($params['id_project'])){
            // save matriks identifikasi dampak
            $checked = 0;
            $inserted = 0;
            DB::beginTransaction();
            //clear items
            ImpactIdentification::where('id_project', $params['id_project'])->delete();
            //insert checked items
            foreach ($params['checked'] as $item){
                foreach ($item['sub'] as $sub){
                    if ($sub['checked']){
                        $created = ImpactIdentification::create([
                            'id_project' => $params['id_project'],
                            'id_project_rona_awal' => $item['id'],
                            'id_project_component' => $sub['id'],
                        ]);
                        if ($created){
                            $inserted++;
                        }
                        $checked++;
                    }
                }
            }
            if ($inserted == $checked){
                DB::commit();
                return response()->json(['code' => 200]);
            } else {
                DB::rollBack();
                return response()->json(['code' => 500]);
            }            
        } else if (isset($params['unit_data'])) {
            // save besran dampak
            DB::beginTransaction();
            $num_impacts = 0;
            $response = [];
            try {
                foreach ($params['unit_data'] as $impact) {
                    if ($impact['id'] < 9990) {
                        //not dummy
                        $num_impacts++;
                        $row = ImpactIdentification::find($impact['id']);
                        if ($row != null) {
                            $row->id_unit = $impact['id_unit'];
                            $row->id_change_type = $impact['id_change_type'];
                            $row->nominal = $impact['nominal'];
                            $row->save();
                            array_push($response, new ImpactIdentificationResource($row));
                        }
                    }
                }
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'code' => 500,
                    'error' => $e->getMessage(),
                ]);
            }
            if (count($response) == $num_impacts) {
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'data' => $response,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'code' => 500,
                    'error' => 'Some rows failed to update.',
                ]);
            }            
        } else {
            $validator = $request->validate([
                'id_project' => 'required',
                'id_component' => 'required',
                'id_rona_awal' => 'required',
            ]);
            DB::beginTransaction();
            $created = ImpactIdentification::create($validator);
            if ($created){
                DB::commit();
            } else {
                DB::rollBack();
            }
            return new ImpactIdentificationResource($created);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ImpactIdentification  $impactIdentification
     * @return \Illuminate\Http\Response
     */
    public function show(ImpactIdentification $impactIdentification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ImpactIdentification  $impactIdentification
     * @return \Illuminate\Http\Response
     */
    public function edit(ImpactIdentification $impactIdentification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ImpactIdentification  $impactIdentification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImpactIdentification $impactIdentification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ImpactIdentification  $impactIdentification
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImpactIdentification $impactIdentification)
    {
        //
    }
}
