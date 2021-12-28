<?php

namespace App\Http\Controllers;

use App\Entity\ImpactIdentification;
use App\Entity\ImpactStudy;
use App\Entity\PotentialImpactEvaluation;
use App\Http\Resources\ImpactIdentificationResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entity\ChangeType;
use App\Entity\EnvManagePlan;
use App\Entity\EnvMonitorPlan;

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
                ->leftJoin('sub_project_components AS pc', 'impact_identifications.id_sub_project_component', '=', 'pc.id')
                ->leftJoin('sub_project_rona_awals AS pra', 'impact_identifications.id_sub_project_rona_awal', '=', 'pra.id')
                ->leftJoin('change_types AS ct', 'impact_identifications.id_change_type', '=', 'ct.id')
                ->leftJoin('components AS c', 'pc.id_component', '=', 'c.id')
                ->leftJoin('rona_awal AS ra', 'pra.id_rona_awal', '=', 'ra.id')
                ->where('impact_identifications.id_project', $request->id_project)
                ->whereNotNull('pc.id')
                ->whereNotNull('pra.id')
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

    private function saveImpactStudies($params)
    {
         // save besran dampak
         DB::beginTransaction();
         $num_impacts = 0;
         $response = [];
         try {
             foreach ($params['study_data'] as $study) {
                 if (array_key_exists('impacts', $study)) {
                     foreach($study['impacts'] as $impact){
                         if ($impact['id'] < 99999999) {
                             //not dummy
                             $num_impacts++;
                             $row = ImpactIdentification::find($impact['id']);
                             if ($row != null) {
                                 $row->id_unit = $impact['id_unit'];
                                 /*if(is_string($impact['id_change_type'])){
                                     $ctype = ChangeType::firstOrCreate(['name' => $impact['id_change_type']]);
                                     $row->id_change_type = $ctype->id;
                                 } else {
                                     $row->id_change_type = $impact['id_change_type'];
                                 }*/
                                 if (($impact['id_change_type'] == 0) &&
                                     (($impact['change_type_name'] != null) &&
                                      (trim($impact['change_type_name']) != ""))){
                                     $ctype = ChangeType::firstOrCreate(['name' => trim($impact['change_type_name'])]);
                                     $row->id_change_type = $ctype->id;
                                 } else {
                                     $row->id_change_type = $impact['id_change_type'];
                                 }
                                 $row->nominal = $impact['nominal'];
                                 $row->potential_impact_evaluation = $impact['potential_impact_evaluation'];
                                 $row->is_hypothetical_significant = $impact['is_hypothetical_significant'];
                                 $row->is_managed = $impact['is_managed'];
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

                                 /** Potential Impact Evaluation */
                                 if(isset($impact['potential_impact_evaluation'])){
                                     foreach($impact['potential_impact_evaluation'] as $pie){
                                         $p = PotentialImpactEvaluation::where('id_impact_identification', $impact['id'])
                                             ->where('id_pie_param', $pie['id_pie_param'])->first();

                                         if (!$p) {
                                             $p = new PotentialImpactEvaluation();
                                             $p->id_impact_identification = $impact['id'];
                                             $p->id_pie_param = $pie['id_pie_param'];
                                         }
                                         $p->text = $pie['text'];
                                         $p->save();
                                     }
                                 }

                                 if ($impact_study_saved){
                                     array_push($response, new ImpactIdentificationResource($row));
                                 }
                             }
                         }
                     }
                 } else {
                     if ($study['id'] < 99999999) {
                         $num_impacts++;
                         $row = ImpactStudy::select('impact_studies.*')
                             ->where('id_impact_identification', $study['id'])
                             ->first();
                         if ($row != null) {
                             $row->id_impact_identification = $study['id'];
                             $row->forecast_method = $study['impact_study']['forecast_method'];
                             $row->required_information = $study['impact_study']['required_information'];
                             $row->data_gathering_method = $study['impact_study']['data_gathering_method'];
                             $row->analysis_method = $study['impact_study']['analysis_method'];
                             $row->evaluation_method = $study['impact_study']['evaluation_method'];
                             $row->save();
                             $impact_study_saved = true;
                         } else {
                             // create new
                             if (ImpactStudy::create($study['impact_study'])){
                                 $impact_study_saved = true;
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
                 'status' => 500,
                 'code' => 500,
                 'error' => $e->getMessage(),
             ]);
         }
         if (count($response) == $num_impacts) {
             DB::commit();
             return response()->json([
                 'status' => 200,
                 'code' => 200,
                 'data' => $response,
             ]);
         } else {
             DB::rollBack();
             return response()->json([
                 'status' => 500,
                 'code' => 500,
                 'error' => 'Some rows failed to update.',
             ]);
         }
    }

    private function saveImpactUnits($params)
    {
        DB::beginTransaction();
        $updated = 0;
        $count = 0;
        $errors = [];
        $response = [];
        foreach ($params['unit_data'] as $impact) {
            if (!$impact['is_stage']) {
                $count++;
                $toUpdate = ImpactIdentification::find($impact['id']);
                if ($toUpdate != null) {
                    try {
                        $empty = 0;
                        if (empty($impact['id_change_type'])) {
                            array_push($errors, 'Perubahan wajib diisi.');
                            $empty++;
                        }
                        if (empty($impact['unit'])) {
                            array_push($errors, 'Besaran wajib diisi.');
                            $empty++;
                        }
                        if ($empty > 0) {
                            continue;
                        }
                        $toUpdate->id_change_type = $impact['id_change_type'];
                        $toUpdate->unit = $impact['unit'];
                        $toUpdate->save();
                        $updated++;
                        array_push($response, $toUpdate);
                    } catch (Exception $e) {
                        array_push($errors, 'Gagal menyimpan Dampak.');
                    }
                }
            }
        }
        if ($updated == $count) {
            DB::commit();
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $response,
            ], 200);
        } else {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                'message' => implode(', ', $errors),
            ], 500);
        }
    }

    private function saveMatriks($params)
    {
        $is_save_dph = isset($params['save_dph']) && $params['save_dph'];
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
    }

    private function saveMatriksUkl($params)
    {
        $updated = 0;
        $errors = [];
        $count = 0;
        $response = [];
        DB::beginTransaction();
        foreach ($params['env_manage_plan_data'] as $impact) {

            if (!$impact['is_stage']) {
                if ($impact['env_manage_plan'] != null) {
                    foreach ($impact['env_manage_plan'] as $plan) {
                        $count++;
                        $toUpdate = EnvManagePlan::find($plan['id']);
                        if ($toUpdate != null) {
                            $toUpdate->form = $plan['form'];
                            $toUpdate->location = $plan['location'];
                            $toUpdate->period = $plan['period'];
                            $toUpdate->executor = $plan['executor'];
                            $toUpdate->supervisor = $plan['supervisor'];
                            $toUpdate->report_recipient = $plan['report_recipient'];
                            $toUpdate->description = $plan['description'];
                            try {
                                $toUpdate->save();
                                $updated++;
                                array_push($response, $toUpdate);
                            } catch (Exception $e) {
                                array_push($errors, 'Gagal menyimpan bentuk kelola \'' . $plan['form'] . '\'');
                            }
                        }
                    }
                }
            }
        }
        if ($updated == $count) {
            DB::commit();
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $response,
            ], 200);
        } else {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                'message' => implode(', ', $errors),
            ], 500);
        }
    }

    private function saveMatriksUpl($params)
    {
        $updated = 0;
        $errors = [];
        $count = 0;
        $response = [];
        DB::beginTransaction();
        foreach ($params['env_monitor_plan_data'] as $impact) {

            if (!$impact['is_stage']) {
                if ($impact['env_monitor_plan'] != null) {
                    foreach ($impact['env_monitor_plan'] as $plan) {
                        $count++;
                        $toUpdate = EnvMonitorPlan::find($plan['id']);
                        if ($toUpdate != null) {
                            $toUpdate->form = $plan['form'];
                            $toUpdate->location = $plan['location'];
                            $toUpdate->period = $plan['period'];
                            $toUpdate->executor = $plan['executor'];
                            $toUpdate->supervisor = $plan['supervisor'];
                            $toUpdate->report_recipient = $plan['report_recipient'];
                            $toUpdate->description = $plan['description'];
                            try {
                                $toUpdate->save();
                                $updated++;
                                array_push($response, $toUpdate);
                            } catch (Exception $e) {
                                array_push($errors, 'Gagal menyimpan bentuk pemantauan \'' . $plan['form'] . '\'');
                            }
                        }
                    }
                }
            }
        }
        if ($updated == $count) {
            DB::commit();
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $response,
            ], 200);
        } else {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                'message' => implode(', ', $errors),
            ], 500);
        }
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
            return $this->saveMatriks($params);
        } else if (isset($params['study_data'])) {
            return $this->saveImpactStudies($params);
        } else if (isset($params['unit_data'])) {
            return $this->saveImpactUnits($params);
        } else if (isset($params['env_manage_plan_data'])) {
            return $this->saveMatriksUkl($params);
        } else if (isset($params['env_monitor_plan_data'])) {
            return $this->saveMatriksUpl($params);
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
     * Get PIE entries
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pieEntries(Request $request){
        if(!$request->id_impact_identification) return response(array(), 200);
        $pies = PotentialImpactEvaluation::whereIn('id_impact_identification', $request->id_impact_identification)
        ->orderBy('id_impact_identification', 'ASC')
        ->orderBy('id_pie_param', 'ASC')
        ->get();
             // ->where('id_pie_param' , $request->id_pie_param)->all();
        return response($pies);
    }

    /**
     * Accomodating DPDPH in  master-detail format
     */

     /**
      * Get Impact Identifications
      * @param \Illuminate\Http\Request $request
      * @return \Illuminate\Http\Response
      */
    public function getImpacts(Request $request){
        $impacts = ImpactIdentification::from('impact_identifications AS ii')
        ->selectRaw('ii.id, ii.id_change_type,
            ct."name" as change_type_name,
            COALESCE(c.id_project_stage, spc.id_project_stage) as project_stage,
            ps.name as stage,
            COALESCE(c."name", spc."name") as komponen,
            COALESCE(ra."name", spra."name") as rona_awal,
            ii.initial_study_plan,
            ii.is_hypothetical_significant,
            ii.is_managed,
            ii.study_length_month,
            ii.study_length_year,
            ii.study_location')
        ->leftJoin('change_types AS ct', 'ii.id_change_type', '=', 'ct.id')
        ->leftJoin('sub_project_rona_awals AS spra', 'ii.id_sub_project_rona_awal', '=', 'spra.id')
        ->leftJoin('sub_project_components AS spc', 'ii.id_sub_project_component', '=', 'spc.id')
        ->leftJoin('components AS c', 'spc.id_component', '=', 'c.id')
        ->leftJoin('rona_awal AS ra', 'spra.id_rona_awal', '=', 'ra.id')
        ->leftJoin('project_stages as ps', 'ps.id', '=', DB::raw('COALESCE(c.id_project_stage, spc.id_project_stage)'))
        ->where('ii.id_project', $request->id_project)
        ->orderBy('ii.id', 'asc')
        ->get();
        return response($impacts);
    }

    /**
     * Save one impact
      * @param \Illuminate\Http\Request $request
      * @return \Illuminate\Http\Response
     */
    public function saveImpact(Request $request){
        if($request && $request->id)
        {
            $impact = ImpactIdentification::find($request->id);
            if($impact){
                $impact->study_length_month = $request->study_length_month;
                $impact->study_length_year = $request->study_length_year;
                $impact->study_location = $request->study_location;
                $impact->is_hypothetical_significant = $request->is_hypothetical_significant;
                $impact->initial_study_plan = $request->initial_study_plan;

                if (($request->id_change_type == 0) &&
                    (($request->change_type_name  != null) &&
                    (trim($request->change_type_name) != ""))){
                    $ctype = ChangeType::firstOrCreate(['name' => trim($request->change_type_name)]);
                    $impact->id_change_type = $ctype->id;
                } else {
                    $impact->id_change_type = $request->id_change_type;
                }
                $impact->save();
                return response($impact);
            }
        }
        return response($request);
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
