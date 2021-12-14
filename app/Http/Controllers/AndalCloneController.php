<?php

namespace App\Http\Controllers;

use App\Entity\ChangeType;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactIdentificationClone;
use App\Entity\ImpactStudyClone;
use App\Entity\PotentialImpactEvalClone;
use App\Http\Resources\ImpactIdentificationCloneResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AndalCloneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check If Clone Exist
        if($request->exist) {
            $this->checkExist($request->idProject);
        }

        if($request->pies) {
            if(!$request->id_impact_identification) return response(array(), 200);
            $pies = PotentialImpactEvalClone::whereIn('id_impact_identification_clone', $request->id_impact_identification)
            ->orderBy('id_impact_identification_clone', 'ASC')
            ->orderBy('id_pie_param', 'ASC')
            ->get();
            return response($pies);
        }

        if($request->join_tables) {
            $list = ImpactIdentificationClone::with('impactStudy')
            ->select('impact_identification_clones.*',
            'pc.id_project_stage',
            'c.id_project_stage AS id_project_stage_master',
            'c.name AS component_name_master',
            'pc.name AS component_name',
            'ra.name AS rona_awal_name_master',
            'pra.name AS rona_awal_name',
            'ct.name AS change_type_name')
            ->leftJoin('sub_project_components AS pc', 'impact_identification_clones.id_sub_project_component', '=', 'pc.id')
            ->leftJoin('sub_project_rona_awals AS pra', 'impact_identification_clones.id_sub_project_rona_awal', '=', 'pra.id')
            ->leftJoin('change_types AS ct', 'impact_identification_clones.id_change_type', '=', 'ct.id')
            ->leftJoin('components AS c', 'pc.id_component', '=', 'c.id')
            ->leftJoin('rona_awal AS ra', 'pra.id_rona_awal', '=', 'ra.id')
            ->where('impact_identification_clones.id_project', $request->id_project)
            ->orderBy('impact_identification_clones.id', 'asc')
            ->get();
            return ImpactIdentificationCloneResource::collection($list);
        }

        if($request->baganAlirDPDH) {
            return $this->baganAlirUklUpl($request->idProject);
        }

        if ($request->id_project){
            $list = ImpactIdentificationClone::where('id_project', $request->id_project)->get();
            return ['data' => $list];
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
        if($request->study_data) {
             // save besran dampak
             DB::beginTransaction();
             $num_impacts = 0;
             $response = [];
             try {
                 foreach ($request->study_data as $study) {
                     if (array_key_exists('impacts', $study)) {
                         foreach($study['impacts'] as $impact){
                             if ($impact['id'] < 99999999) {
                                 //not dummy
                                 $num_impacts++;
                                 
                                $row = ImpactIdentificationClone::find($impact['id']);

                                 if ($row != null) {
                                     $row->id_unit = $impact['id_unit'];
                                    //  if(is_string($impact['id_change_type'])){
                                    //      $ctype = ChangeType::firstOrCreate(['name' => $impact['id_change_type']]);
                                    //      $row->id_change_type = $ctype->id;
                                    //  } else {
                                    //      $row->id_change_type = $impact['id_change_type'];
                                    //  }
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
                                         $study = ImpactStudyClone::where('id_impact_identification_clone', $impact['id'])
                                             ->first();
                                         if ($study != null) {
                                             $study->id_impact_identification_clone = $impact['id'];
                                             $study->forecast_method = $impact['impact_study']['forecast_method'];
                                             $study->required_information = $impact['impact_study']['required_information'];
                                             $study->data_gathering_method = $impact['impact_study']['data_gathering_method'];
                                             $study->analysis_method = $impact['impact_study']['analysis_method'];
                                             $study->evaluation_method = $impact['impact_study']['evaluation_method'];
                                             $study->save();
                                             $impact_study_saved = true;
                                         } else {
                                             // create new
                                             if (ImpactStudyClone::create($impact['impact_study'])){
                                                 $impact_study_saved = true;
                                             }
                                         }
                                     }
 
                                     /** Potential Impact Evaluation */
                                     if(isset($impact['potential_impact_evaluation'])){
                                         foreach($impact['potential_impact_evaluation'] as $pie){
                                             $p = PotentialImpactEvalClone::where('id_impact_identification_clone', $impact['id'])
                                                 ->where('id_pie_param', $pie['id_pie_param'])->first();
 
                                             if (!$p) {
                                                 $p = new PotentialImpactEvalClone();
                                                 $p->id_impact_identification_clone = $impact['id'];
                                                 $p->id_pie_param = $pie['id_pie_param'];
                                             }
                                             $p->text = $pie['text'];
                                             $p->save();
                                         }
                                     }
 
                                     if ($impact_study_saved){
                                         array_push($response, new ImpactIdentificationCloneResource($row));
                                     }
                                 }
                             }
                         }
                     } else {
                         if ($study['id'] < 99999999) {
                             $num_impacts++;
                             $row = ImpactStudyClone::where('id_impact_identification_clone', $study['id'])
                                 ->first();
                             if ($row != null) {
                                 $row->id_impact_identification_clone = $study['id'];
                                 $row->forecast_method = $study['impact_study']['forecast_method'];
                                 $row->required_information = $study['impact_study']['required_information'];
                                 $row->data_gathering_method = $study['impact_study']['data_gathering_method'];
                                 $row->analysis_method = $study['impact_study']['analysis_method'];
                                 $row->evaluation_method = $study['impact_study']['evaluation_method'];
                                 $row->save();
                                 $impact_study_saved = true;
                             } else {
                                 // create new
                                 if (ImpactStudyClone::create($study['impact_study'])){
                                     $impact_study_saved = true;
                                 }
                             }
 
                             if ($impact_study_saved){
                                 array_push($response, new ImpactIdentificationCloneResource($row));
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

    private function checkExist($id) {
        $old_impact = ImpactIdentification::where('id_project', $id)->with(['impactStudy','potentialImpactEvaluation'])->get();
        foreach($old_impact as $o) {
            $imp = ImpactIdentificationClone::where('id_impact_identification', $o->id)->with(['impactStudy', 'potentialImpactEvaluation'])->first();
            if($imp) {
                $imp->id_sub_project_component = $o->id_sub_project_component;
                $imp->id_sub_project_rona_awal = $o->id_sub_project_rona_awal;
                $imp->save();

                if($imp->impactStudy) {
                    $study = ImpactStudyClone::where('id_impact_identification_clone', $imp->id)->first();
                    $study->forecast_method = isset($o->impactStudy) ? $o->impactStudy->forecast_method : null;
                    $study->required_information = isset($o->impactStudy) ? $o->impactStudy->required_information : null;
                    $study->data_gathering_method = isset($o->impactStudy) ? $o->impactStudy->data_gathering_method : null;
                    $study->analysis_method = isset($o->impactStudy) ? $o->impactStudy->analysis_method : null;
                    $study->evaluation_method = isset($o->impactStudy) ? $o->impactStudy->evaluation_method : null;
                    $study->save();
                } else {
                    $study = new ImpactStudyClone();
                    $study->id_impact_identification_clone = $imp->id;
                    $study->forecast_method = isset($o->impactStudy) ? $o->impactStudy->forecast_method : null;
                    $study->required_information = isset($o->impactStudy) ? $o->impactStudy->required_information : null;
                    $study->data_gathering_method = isset($o->impactStudy) ? $o->impactStudy->data_gathering_method : null;
                    $study->analysis_method = isset($o->impactStudy) ? $o->impactStudy->analysis_method : null;
                    $study->evaluation_method = isset($o->impactStudy) ? $o->impactStudy->evaluation_method : null;
                    $study->save();
                }
                
            } else {
                $imp = new ImpactIdentificationClone();
                $imp->id_impact_identification = $o->id;
                $imp->id_project = $o->id_project;
                $imp->id_change_type = $o->id_change_type;
                $imp->id_unit = $o->id_unit;
                $imp->nominal = $o->nominal;
                $imp->potential_impact_evaluation = $o->potential_impact_evaluation;
                $imp->is_hypothetical_significant = $o->is_hypothetical_significant;
                $imp->initial_study_plan = $o->initial_study_plan;
                $imp->study_location = $o->study_location;
                $imp->study_length_month = $o->study_length_month;
                $imp->study_length_year = $o->study_length_year;
                $imp->id_sub_project_component = $o->id_sub_project_component;
                $imp->id_sub_project_rona_awal = $o->id_sub_project_rona_awal;
                $imp->is_managed = $o->is_managed;
                $imp->save();

                if($o->impactStudy) {
                    $study = new ImpactStudyClone();
                    $study->id_impact_identification_clone = $imp->id;
                    $study->forecast_method = $o->impactStudy->forecast_method;
                    $study->required_information = $o->impactStudy->required_information;
                    $study->data_gathering_method = $o->impactStudy->data_gathering_method;
                    $study->analysis_method = $o->impactStudy->analysis_method;
                    $study->evaluation_method = $o->impactStudy->evaluation_method;
                    $study->save();
                }
    
                if($o->potentialImpactEvaluation) {
                    foreach($o->potentialImpactEvaluation as $p) {
                        $potential = new PotentialImpactEvalClone();
                        $potential->id_impact_identification_clone = $imp->id;
                        $potential->id_pie_param = $p->id_pie_param;
                        $potential->text = $p->text;
                        $potential->save();
                    }
                }
            }
        }
    }

    private function baganAlirUklUpl($id)
    {
        $getRencanaKegiatan = DB::table('sub_projects')
            ->select('sub_projects.name')
            ->leftJoin('projects', 'projects.id', '=', 'sub_projects.id_project')
            ->where('sub_projects.id_project', '=', $id)
            ->where('sub_projects.type', '=', 'utama')
            ->get();

        $getKegiatanLain = DB::table('sub_projects')
            ->select('sub_projects.name')
            ->leftJoin('projects', 'projects.id', '=', 'sub_projects.id_project')
            ->where('sub_projects.id_project', '=', $id)
            ->where('sub_projects.type', '=', 'pendukung')
            ->get();

        $getFeedbackConcern = DB::table('announcements')
            ->select('feedbacks.concern')
            ->leftJoin('feedbacks', 'feedbacks.announcement_id', '=', 'announcements.id')
            ->leftJoin('projects', 'projects.id', '=', 'announcements.project_id')
            ->where('projects.id', '=', $id)
            ->whereNotNull('feedbacks.concern')
            ->get();

        $getFeedbackExpectation = DB::table('announcements')
            ->select('feedbacks.expectation')
            ->leftJoin('feedbacks', 'feedbacks.announcement_id', '=', 'announcements.id')
            ->leftJoin('projects', 'projects.id', '=', 'announcements.project_id')
            ->where('projects.id', '=', $id)
            ->whereNotNull('feedbacks.expectation')
            ->get();


        $getRonaAwal = DB::table('sub_projects')
            ->select('rona_awal.name')
            ->join('sub_project_components', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->join('sub_project_rona_awals', 'sub_project_rona_awals.id_sub_project_component', '=', 'sub_project_components.id')
            ->join('projects', 'projects.id', '=', 'sub_projects.id_project')
            ->join('rona_awal', 'rona_awal.id', '=', 'sub_project_rona_awals.id_rona_awal')
            ->where('projects.id', '=', $id)
            ->get();

        $getDampakPentingHipotetik = DB::table('impact_study_clones')
            ->select(
                'project_stages.name',
                'impact_identification_clones.potential_impact_evaluation',
                'impact_identification_clones.is_hypothetical_significant'
            )
            ->selectRaw('ROW_NUMBER () OVER (ORDER BY impact_study_clones.id) as number')
            ->leftJoin('impact_identification_clones', 'impact_study_clones.id_impact_identification_clone', '=', 'impact_identification_clones.id')
            ->leftJoin('sub_project_components', 'sub_project_components.id', '=', 'impact_identification_clones.id_sub_project_component')
            ->leftJoin('project_stages', 'project_stages.id', '=', 'sub_project_components.id_project_stage')
            ->leftJoin('projects', 'projects.id', '=', 'impact_identification_clones.id_project')
            ->where('projects.id', '=', $id)
            ->where('impact_identification_clones.is_hypothetical_significant', '=', 'true')
            ->get();

        return response()->json([
            'rencana_kegiatan' => $getRencanaKegiatan,
            'kegiatan_lain' => $getKegiatanLain,
            'rona_awal' => $getRonaAwal,
            'feedback_concern' => $getFeedbackConcern,
            'feedback_expectation' => $getFeedbackExpectation,
            'dampak_penting_hipotetik' => $getDampakPentingHipotetik,
        ]);
    }
}
