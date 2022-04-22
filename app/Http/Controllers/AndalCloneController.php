<?php

namespace App\Http\Controllers;

use App\Entity\ChangeType;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactIdentificationClone;
use App\Entity\ImpactStudy;
use App\Entity\ImpactStudyClone;
use App\Entity\PotentialImpactEvalClone;
use App\Entity\PotentialImpactEvaluation;
use App\Http\Resources\ImpactIdentificationCloneResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entity\SubProjectComponent;
use App\Entity\SubProject;
use App\Entity\SubProjectRonaAwal;
use App\Entity\ImpactKegiatanLainSekitar;
use App\Entity\ProjectComponent;
use App\Entity\Project;
use App\Entity\ProjectRonaAwal;
use App\Entity\ProjectKegiatanLainSekitar;

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
            'c.id_project_stage',
            'c.id_project_stage AS id_project_stage_master',
            'c.name AS component_name',
            'c.name AS component_name',
            'ra.name AS rona_awal_name_master',
            'ra.name AS rona_awal_name',
            'ct.name AS change_type_name')
            ->leftJoin('project_components AS pc', 'impact_identification_clones.id_project_component', '=', 'pc.id')
            ->leftJoin('project_rona_awals AS pra', 'impact_identification_clones.id_project_rona_awal', '=', 'pra.id')
            ->leftJoin('change_types AS ct', 'impact_identification_clones.id_change_type', '=', 'ct.id')
            ->leftJoin('components AS c', 'pc.id_component', '=', 'c.id')
            ->leftJoin('rona_awal AS ra', 'pra.id_rona_awal', '=', 'ra.id')
            ->where('impact_identification_clones.id_project', $request->id_project)
            ->whereNotNull('impact_identification_clones.id_project_component')
            ->whereNotNull('impact_identification_clones.id_project_rona_awal')
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
                                 if(!array_key_exists('id_impact_identification_clone', $study['impact_study']) && array_key_exists('id_impact_identification', $study['impact_study'])) {
                                     $study['impact_study']['id_impact_identification_clone'] = $study['impact_study']['id_impact_identification'];
                                 }
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
        /* MASTER KOMPONEN KEGIATAN */
        $mkk_andal = ProjectComponent::where(['id_project' => $id, 'is_andal' => true])
            ->pluck('id_component');


        $mkk = ProjectComponent::where(['id_project' => $id,'is_andal' => false])
            ->whereNotIn('id_component', $mkk_andal)->get();

        foreach($mkk as $kk){
            $new_kk = ProjectComponent::create([
                'id_project' => $id,
                'id_component' => $kk->id_component,
                'description' => $kk->description,
                'measurement' => $kk->measurement,
                'is_andal' => true,
            ]);
        }
        /* END OF MASTER KOMPONEN KEGIATAN */

        /* MASTER KOMPONEN LINGKUNGAN */
        $mkl_andal = ProjectRonaAwal::where(['id_project' => $id, 'is_andal' => true])
            ->pluck('id_rona_awal');

        $mkl = ProjectRonaAwal::where(['id_project' => $id, 'is_andal' => false])
            ->whereNotIn('id_rona_awal', $mkl_andal)->get();

        foreach($mkl as $kl){
            $new_kl = ProjectRonaAwal::create([
                'is_andal' => true,
                'id_rona_awal' => $kl->id_rona_awal,
                'id_project' => $id,
                'measurement' => $kl->measurement,
                'description' => $kl->description
            ]);
        }
        /* END OF MASTER KOMPONEN LINGKUNGAN */

        /* MASTER KOMPONEN KEGIATAN LAIN SEKITAR */
        $mkkls_andal = ProjectKegiatanLainSekitar::where([
            'project_id' => $id,
            'is_andal' => true,
        ])->pluck('kegiatan_lain_sekitar_id');
        $mkkls = ProjectKegiatanLainSekitar::where([
            'is_andal' =>false,
            'project_id' => $id,
        ])->whereNotIn('kegiatan_lain_sekitar_id', $mkkls_andal)->get();

        foreach($mkkls as $kkls){
            $new_kkls = ProjectKegiatanLainSekitar::create([
                'project_id' => $id,
                'kegiatan_lain_sekitar_id' => $kkls->kegiatan_lain_sekitar_id,
                'is_andal' => true,
                'address' => $kkls->address,
                'province_id' => $kkls->province_id,
                'district_id' => $kkls->district_id,
                'description' => $kkls->description,
                'measurement' => $kkls->measurement,
            ]);

        }
        /* END OF MASTER KOMPONEN KEGIATAN LAIN SEKITAR */

        /* PELINGKUPAN */
        $sp = SubProject::where('id_project', $id)->pluck('id');
        $spc = SubProjectComponent::where('is_andal', false)->whereIn('id_sub_project', $sp)->get();
        foreach($spc as $comp){
            // get sub_project_rona_awal
            $new_comp = SubProjectComponent::firstOrNew([
                'id_sub_project' => $comp->id_sub_project,
                'id_component' => $comp->id_component,
                'is_andal' => true
            ]);
            if(!$new_comp->exists){
                $new_comp->description_specific = $comp->description_specific;
                $new_comp->unit = $comp->unit;
                $new_comp->save();
            }

            $spra = SubProjectRonaAwal::where('id_sub_project_component', $comp->id)->get();
            if($spra){
                foreach($spra as $ra){
                    $new_ra = SubProjectRonaAwal::firstOrNew([
                        'id_sub_project_component' => $new_comp->id,
                        'id_rona_awal' => $ra->id_rona_awal,
                        'is_andal' => true
                    ]);
                    if(!$new_ra->exists){
                        // $new_ra->name = $ra->name;
                        // $new_ra->id_component_type = $ra->id_component_type;
                        $new_ra->description_common = $ra->description_common;
                        $new_ra->description_specific = $ra->description_specific;
                        $new_ra->unit = $ra->unit;
                        $new_ra->save();
                    }
                }
            }
        }
        /* END OF PELINGKUPAN */


        $old_impact = ImpactIdentification::where('id_project', $id)->get();
        $new_impact = ImpactIdentificationClone::select('id', 'id_project', 'id_impact_identification')->where('id_project', $id)->get();

        $new_ids = [];
        foreach($new_impact as $ni) {
            $new_ids[] = $ni->id_impact_identification;
        }

        foreach($old_impact as $oi) {
            if(!in_array($oi->id, $new_ids)) {
                $imp = new ImpactIdentificationClone();
                $imp->id_impact_identification = $oi->id;
                $imp->id_project = $oi->id_project;
                $imp->id_change_type = $oi->id_change_type;
                $imp->id_unit = $oi->id_unit;
                $imp->nominal = $oi->nominal;
                $imp->potential_impact_evaluation = $oi->potential_impact_evaluation;
                $imp->is_hypothetical_significant = $oi->is_hypothetical_significant;
                $imp->initial_study_plan = $oi->initial_study_plan;
                $imp->study_location = $oi->study_location;
                $imp->study_length_month = $oi->study_length_month;
                $imp->study_length_year = $oi->study_length_year;
                $imp->id_project_component = $oi->id_project_component;
                $imp->id_project_rona_awal = $oi->id_project_rona_awal;
                $imp->is_managed = $oi->is_managed;
                $imp->save();

                // IMPACT STUDY
                $is = ImpactStudy::where('id_impact_identification', $oi->id)->first();
                if($is) {
                    $new_is = new ImpactStudyClone();
                    $new_is->id_impact_identification_clone = $imp->id;
                    $new_is->forecast_method = $is->forecast_method;
                    $new_is->required_information = $is->required_information;
                    $new_is->data_gathering_method = $is->data_gathering_method;
                    $new_is->analysis_method = $is->analysis_method;
                    $new_is->evaluation_method = $is->evaluation_method;
                    $new_is->save();
                }

                // POTENTIAL IMPACT EVALUATION
                $piv = PotentialImpactEvaluation::where('id_impact_identification', $oi->id)->get();
                foreach($piv as $p) {
                    $potential = new PotentialImpactEvalClone();
                    $potential->id_impact_identification_clone = $imp->id;
                    $potential->id_pie_param = $p->id_pie_param;
                    $potential->text = $p->text;
                    $potential->save();
                }

                // IMPACT KEGIATAN LAIN SEKITAR
                $ikls = ImpactKegiatanLainSekitar::where([
                    'id_impact_identification' =>  $oi->id,
                    'is_andal' => false

                    ])->get();
                foreach($ikls as $kls){
                    $fka_kls = ProjectKegiatanLainSekitar::find($kls->id_project_kegiatan_lain_sekitar);
                    if($fka_kls){
                        $andal_kls = ProjectKegiatanLainSekitar::where([
                            'is_andal' => true,
                            'project_id' => $fka_kls->project_id,
                            'kegiatan_lain_sekitar_id' => $fka_kls->kegiatan_lain_sekitar_id,
                        ])->first();
                        if($andal_kls){
                            $new_ikls = ImpactKegiatanLainSekitar::firstOrCreate([
                                'id_impact_identification' => $imp->id,
                                'id_project_kegiatan_lain_sekitar' => $andal_kls->id,
                                'is_andal' => true
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json(['message' => 'success']);
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
