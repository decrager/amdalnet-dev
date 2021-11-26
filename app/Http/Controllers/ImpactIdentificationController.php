<?php

namespace App\Http\Controllers;

use App\Entity\ImpactIdentification;
use App\Entity\ImpactStudy;
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
        if ($request->join_tables){
            $list = ImpactIdentification::with('impactStudy')
                ->select('impact_identifications.*',
                'pc.id_project_stage',
                'c.id_project_stage AS id_project_stage_master',
                'c.name AS component_name_master',
                'pc.name AS component_name',
                'ra.name AS rona_awal_name_master',
                'pra.name AS rona_awal_name',
                'ct.name AS change_type_name')
                ->leftJoin('project_components AS pc', 'impact_identifications.id_project_component', '=', 'pc.id')
                ->leftJoin('project_rona_awals AS pra', 'impact_identifications.id_project_rona_awal', '=', 'pra.id')
                ->leftJoin('change_types AS ct', 'impact_identifications.id_change_type', '=', 'ct.id')
                ->leftJoin('components AS c', 'pc.id_component', '=', 'c.id')
                ->leftJoin('rona_awal AS ra', 'pra.id_rona_awal', '=', 'ra.id')
                ->where('impact_identifications.id_project', $request->id_project)
                ->orderBy('impact_identifications.id', 'asc')
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
        $is_save_dph = isset($params['save_dph']) && $params['save_dph'];
        if (isset($params['checked']) && isset($params['id_project'])){
            // save matriks identifikasi dampak
            $checked = 0;
            $inserted = 0;
            DB::beginTransaction();
            //clear items
            if ($is_save_dph) {
                $impacts = ImpactIdentification::where('id_project', $params['id_project'])->get();
                foreach ($impacts as $impact) {
                    $impact->is_hypothetical_significant = false;
                    $impact->save();
                }
            } else {
                ImpactIdentification::where('id_project', $params['id_project'])->delete();
            }
            //insert checked items
            try {
                foreach ($params['checked'] as $item){
                    if ($item['id'] < 99999999) {
                        foreach ($item['sub'] as $sub){
                            if ($sub['checked']){
                                if ($is_save_dph) {
                                    $impact = ImpactIdentification::select('*')
                                        ->where('id_sub_project_rona_awal', $item['id'])
                                        ->where('id_sub_project_component', $sub['id'])
                                        ->first();
                                    if ($impact != null) {
                                        $impact->is_hypothetical_significant = true;
                                        $impact->save();
                                    }
                                } else {
                                    $created = ImpactIdentification::create([
                                        'id_project' => $params['id_project'],
                                        'id_sub_project_rona_awal' => $item['id'],
                                        'id_sub_project_component' => $sub['id'],
                                    ]);
                                    if ($created){
                                        $inserted++;
                                    }
                                    $checked++;
                                }
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                return response()->json(['code' => 500, 'error' => $e->getMessage()]);
            }
            if ($is_save_dph) {
                DB::commit();
                return response()->json(['code' => 200]);
            } else {
                if ($inserted == $checked){
                    DB::commit();
                    return response()->json(['code' => 200]);
                } else {
                    DB::rollBack();
                    return response()->json(['code' => 500]);
                }       
            }     
        } else if (isset($params['study_data'])) {
            // save besran dampak
            DB::beginTransaction();
            $num_impacts = 0;
            $response = [];
            try {
                foreach ($params['study_data'] as $impact) {
                    if ($impact['id'] < 99999999) {
                        //not dummy
                        $num_impacts++;
                        $row = ImpactIdentification::find($impact['id']);
                        if ($row != null) {
                            $row->id_unit = $impact['id_unit'];
                            $row->id_change_type = $impact['id_change_type'];
                            $row->nominal = $impact['nominal'];
                            $row->potential_impact_evaluation = $impact['potential_impact_evaluation'];
                            $row->is_hypothetical_significant = $impact['is_hypothetical_significant'];
                            $row->initial_study_plan = $impact['initial_study_plan'];
                            $row->study_location = $impact['study_location'];
                            $row->study_length_year = $impact['study_length_year'];
                            $row->study_length_month = $impact['study_length_month'];
                            $row->save();
                            // save impact_study
                            $impact_study_saved = false;
                            if (isset($impact['impact_study'])) {
                                $study = ImpactStudy::select('impact_studies.*')
                                    ->where('id_impact_identification', $impact['id'])
                                    ->first();
                                if ($study != null) {
                                    $study->id_impact_identification = $impact['id'];
                                    $study->forecast_method = $impact['impact_study']['forecast_method'];
                                    $study->required_information = $impact['impact_study']['required_information'];
                                    $study->data_gathering_method = $impact['impact_study']['data_gathering_method'];
                                    $study->analysis_method = $impact['impact_study']['analysis_method'];
                                    $study->evaluation_method = $impact['impact_study']['evaluation_method'];
                                    $study->save();
                                    $impact_study_saved = true;
                                } else {
                                    // create new
                                    if (ImpactStudy::create($impact['impact_study'])){
                                        $impact_study_saved = true;
                                    }
                                }
                            }
                            if ($impact_study_saved){
                                array_push($response, new ImpactIdentificationResource($row));
                            }
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
        } else if (isset($params['id_project']) && isset($params['id_sub_project_component'])
            && isset($params['id_sub_project_rona_awal'])) {
            $validator = $request->validate([
                'id_project' => 'required',
                'id_sub_project_component' => 'required',
                'id_sub_project_rona_awal' => 'required',
            ]);
            DB::beginTransaction();
            $created = ImpactIdentification::create($validator);
            if ($created){
                DB::commit();
                return $created;
            } else {
                DB::rollBack();
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
