<?php

namespace App\Http\Controllers;

use App\Entity\Comment;
use App\Entity\EnvImpactAnalysis;
use App\Entity\EnvManageApproach;
use App\Entity\EnvManagePlan;
use App\Entity\EnvMonitorPlan;
use App\Entity\EnvPlanForm;
use App\Entity\EnvPlanIndicator;
use App\Entity\EnvPlanInstitution;
use App\Entity\EnvPlanLocation;
use App\Entity\EnvPlanSource;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactIdentificationClone;
use App\Entity\Project;
use App\Entity\ProjectStage;
use App\Entity\RklRplComment;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

use function PHPUnit\Framework\isEmpty;

class MatriksRKLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->checkDocument) {
            if (!File::exists(storage_path('app/public/workspace/'))) {
                return 'false';
            }
    
            $save_file_name = $request->idProject . '-rkl-rpl' . '.docx';
    
            if (File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
                return 'true';
            }

            return 'false';
        }

        if($request->projectName) {
            $project = Project::findOrFail($request->idProject);
            return $project->project_title;
        }

        if($request->checkWorkspace) {
            return $this->checkWorkspace($request->idProject);
        }

        if($request->docs) {
            $save_file_name = $request->idProject .'-rkl-rpl' . '.docx'; 
            if (!File::exists(storage_path('app/public/workspace/'))) {
                File::makeDirectory(storage_path('app/public/workspace/'));
            }

            if (File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
                return response()->json(['message' => 'success']);
            }

            $templateProcessor = new TemplateProcessor('template_rkl_rpl.docx');

            $poinA = [];
            $poinB = [];

            $ids = [4,1,2,3];
            $stages = ProjectStage::select('id', 'name')->get()->sortBy(function($model) use($ids) {
                return array_search($model->getKey(),$ids);
            });

            $project = Project::findOrFail($request->idProject);

            // ========= PROJECT ADDRESS ========== //
            $project_district = '';
            $project_province = '';
            if($project->address) {
                if($project->address->first()) {
                    $project_district = $project->address->first()->district;
                    $project_province = $project->address->first()->province;
                }
            }

            // ==== MANAGE APPROACH === //
            $manage_approach = EnvManageApproach::where('id_project', $request->idProject)->get();
            // TECHNOLOGY
            $tech_approach_data = [];
            $tech_approach = $manage_approach->where('approach_type', 'technology');
            foreach($tech_approach as $ta) {
                $tech_approach_data[]['tech_approach'] = count($tech_approach_data) + 1 . '. ' . $ta->description;
            }
            // SOCIAL ECONOMY
            $soc_eco_approach_data = [];
            $soc_eco_approach = $manage_approach->where('approach_type', 'social_economy');
            foreach($soc_eco_approach as $so) {
                $soc_eco_approach_data[]['soc_eco_approach'] = count($soc_eco_approach_data) + 1 . '. ' . $so->description;
            }
            // INSTITUTION
            $institution_approach_data = [];
            $institution_approach = $manage_approach->where('approach_type', 'institution');
            foreach($institution_approach as $ins) {
                $institution_approach_data[]['institution_approach'] = count($institution_approach_data) + 1 . '. ' . $ins->description;
            }
            
            // === IMPACT SOURCE === //
            $impact_source = EnvPlanSource::whereHas('impactIdentification', function($q) use($request) {
                $q->where('id_project', $request->idProject);
            })->get();

            // === INDICATOR === //
            $indicator = EnvPlanIndicator::whereHas('impactIdentification', function($q) use($request) {
                $q->where('id_project', $request->idProject);
            })->get();

            // === INSTITUTION === //
            $institution = EnvPlanInstitution::whereHas('impactIdentification', function($q) use($request) {
                $q->where('id_project', $request->idProject);
            })->get();

            $poinA = $this->getPoinA($stages, $request->idProject, $impact_source, $indicator, $institution);
            $poinB = $this->getPoinB($stages, $request->idProject, $impact_source, $indicator, $institution);

            $poinAp = $this->getPoinARpl($stages, $request->idProject, $impact_source, $indicator, $institution);
            $poinBp = $this->getPoinBRpl($stages, $request->idProject, $impact_source, $indicator, $institution);

            $templateProcessor->setValue('project_title', $project->project_title);
            $templateProcessor->setValue('district', $project_district);
            $templateProcessor->setValue('province', $project_province);
            $templateProcessor->setValue('pemrakarsa', $project->initiator->name);

            $templateProcessor->cloneBlock('tech_approach_block', count($tech_approach_data), true, false, $tech_approach_data);
            $templateProcessor->cloneBlock('soc_eco_approach_block', count($soc_eco_approach_data), true, false, $soc_eco_approach_data);
            $templateProcessor->cloneBlock('institution_approach_block', count($institution_approach_data), true, false, $institution_approach_data);

            $templateProcessor->cloneRowAndSetValues('a_pra_konstruksi_rkl', $poinA['a_pra_konstruksi_rkl']);
            $templateProcessor->cloneRowAndSetValues('a_konstruksi_rkl', $poinA['a_konstruksi_rkl']);
            $templateProcessor->cloneRowAndSetValues('a_operasi_rkl', $poinA['a_operasi_rkl']);
            $templateProcessor->cloneRowAndSetValues('a_pasca_operasi_rkl', $poinA['a_pasca_operasi_rkl']);
            $templateProcessor->cloneRowAndSetValues('b_pra_konstruksi_rkl', $poinB['b_pra_konstruksi_rkl']);
            $templateProcessor->cloneRowAndSetValues('b_konstruksi_rkl', $poinB['b_konstruksi_rkl']);
            $templateProcessor->cloneRowAndSetValues('b_operasi_rkl', $poinB['b_operasi_rkl']);
            $templateProcessor->cloneRowAndSetValues('b_pasca_operasi_rkl', $poinB['b_pasca_operasi_rkl']);

            $templateProcessor->cloneRowAndSetValues('a_pra_konstruksi_rpl', $poinAp['a_pra_konstruksi_rpl']);
            $templateProcessor->cloneRowAndSetValues('a_konstruksi_rpl', $poinAp['a_konstruksi_rpl']);
            $templateProcessor->cloneRowAndSetValues('a_operasi_rpl', $poinAp['a_operasi_rpl']);
            $templateProcessor->cloneRowAndSetValues('a_pasca_operasi_rpl', $poinAp['a_pasca_operasi_rpl']);
            $templateProcessor->cloneRowAndSetValues('b_pra_konstruksi_rpl', $poinBp['b_pra_konstruksi_rpl']);
            $templateProcessor->cloneRowAndSetValues('b_konstruksi_rpl', $poinBp['b_konstruksi_rpl']);
            $templateProcessor->cloneRowAndSetValues('b_operasi_rpl', $poinBp['b_operasi_rpl']);
            $templateProcessor->cloneRowAndSetValues('b_pasca_operasi_rpl', $poinBp['b_pasca_operasi_rpl']);

            $templateProcessor->saveAs(storage_path('app/public/workspace/' . $save_file_name));
            

            return response()->json(['message' => 'success']);

            // return [
            //     'poinA' => $poinA,
            //     'poinB' => $poinBp
            // ];

            
        }

        if($request->comment) {
            if($request->role == 'true') {
                $comments = RklRplComment::where([['id_project', $request->idProject], ['id_user', $request->idUser]])->orderBy('id', 'DESC')->with(['user' => function($q) {
                    $q->select(['id', 'name']);
                }])->get();
            } else {
                $comments = RklRplComment::where('id_project', $request->idProject)->orderBy('id', 'DESC')->with(['user' => function($q) {
                    $q->select(['id', 'name']);
                }])->get();
            }
            return $comments;
        }

        if($request->project) {
            return Project::whereHas('impactIdentifications', function($q) {
                $q->whereHas('envImpactAnalysis');
            })->get();
        }

        if($request->lastTime && $request->idProject) {
            $id_project = $request->idProject;
            $time = EnvManagePlan::whereHas('impactIdentification', function($q) use($id_project) {
                $q->where('id_project', $id_project);
            })->orderBy('updated_at', 'DESC')->first();

            if($time) {
                return 'Diperbarui ' . $time->updated_at->locale('id')->diffForHumans();
            } else {
                return null;
            }
        }

        if($request->idProject) {
            $ids = [4,1,2,3];
            $stages = ProjectStage::select('id', 'name')->get()->sortBy(function($model) use($ids) {
                return array_search($model->getKey(),$ids);
            });
            
            $project = Project::where('id', $request->idProject)->whereHas('impactIdentificationsClone', function($query) {
                $query->whereHas('envManagePlan');
            })->first();

            $type = $project ? 'update' : 'new';

            return $this->getEnvManagePlan($request->idProject, $stages, $type);
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
        if($request->map) {
            if($request->hasFile('map_file')) {
                //create file
                $file = $request->file('map_file');
                $name = '/map/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
      
                $project = Project::findOrFail($request->idProject);
                $project->map_rkl = Storage::url($name);
                $project->save();
   
                return response()->json(['message' => 'success']);
           }
   
           return response()->json(['message' => 'failed'], 404);
        }

        if($request->workspace) {
            if(Storage::disk('public')->exists('workspace/' . $request->idProject . '-rkl-rpl.docx')) {
                return;
            }

            if($request->hasFile('docx')) {
                //create file
                $file = $request->file('docx');
                $name = '/workspace/' . $request->idProject . '-rkl-rpl' . '.docx';
                $file->storePubliclyAs('public', $name);
   
                return response()->json(['message' => 'success']);
           }

           return;
        }

        if($request->type == 'impact-comment') {
            $comment = new Comment();
            $comment->id_user = $request->id_user;
            $comment->id_impact_identification = $request->id_impact_identification;
            $comment->description = $request->description;
            $comment->column_type = $request->column_type;
            $comment->document_type = 'rkl';
            $comment->is_checked = false;
            $comment->save();

            return [
                    'id' => $comment->id,
                    'id_impact_identification' => $comment->id_impact_identification,
                    'created_at' => $comment->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                    'user' => $comment->user->name,
                    'is_checked' => $comment->is_checked,
                    'description' => $comment->description,
                    'column_type' => $comment->column_type,
                    'replies' => []
            ];
        }

        if($request->type == 'impact-comment-reply') {
            $comment = new Comment();
            $comment->id_user = $request->id_user;
            $comment->id_impact_identification = $request->id_impact_identification;
            $comment->description = $request->description;
            $comment->document_type = 'rkl';
            $comment->reply_to = $request->id_comment;
            $comment->save();

            return [
                'id' => $comment->id,
                'created_at' => $comment->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                'description' => $comment->description
            ];
        }

        if($request->type == 'checked-comment') {
            $comment = Comment::findOrFail($request->id);
            if($comment->is_checked) {
                $comment->is_checked = false;
            } else {
                $comment->is_checked = true;
            }
            $comment->save();

            return $comment->is_checked;
        }

        if($request->type == 'comment') {
            $comment = new RklRplComment();
            $comment->id_project = $request->idProject;
            $comment->id_user = $request->idUser;
            $comment->comment = $request->comment;
            $comment->save();

            return RklRplComment::whereKey($comment->id)->with('user')->first();
        }

        $manage = $request->manage;
        $envManage = null;
        $ids = [];
        
        for($i = 0; $i < count($manage); $i++) {
            if($request->type == 'new') {
                $envManage = new EnvManagePlan();
                $envManage->id_impact_identifications = $manage[$i]['id'];
            } else {
                $envManage = EnvManagePlan::where('id_impact_identifications', $manage[$i]['id'])->first();
                if(!$envManage) {
                    $envManage = new EnvManagePlan();
                    $envManage->id_impact_identifications = $manage[$i]['id'];
                }
                $ids[] = $manage[$i]['id'];
            }

            $envManage->period = $manage[$i]['period_number'] . '-' . $manage[$i]['period_description'];
            $envManage->save();

            // === INSTITUTION === //
            $institution = EnvPlanInstitution::where('id_impact_identification', $manage[$i]['id'])->first();
            if(!$institution) {
                $institution = new EnvPlanInstitution();
                $institution->id_impact_identification = $manage[$i]['id'];
            }

            $institution->executor = $manage[$i]['executor'];
            $institution->supervisor = $manage[$i]['supervisor'];
            $institution->report_recipient = $manage[$i]['report_recipient'];
            $institution->save();

             // === IMPACT SOURCE === //
             $impact_source = $manage[$i]['impact_source'];
             if(count($impact_source) > 0) {
                 for($a = 0; $a < count($impact_source); $a++) {
                     $imp_source = null;
                     if($impact_source[$a]['id'] !== null) {
                         $imp_source = EnvPlanSource::findOrFail($impact_source[$a]['id']);
                     } else {
                        $imp_source = new EnvPlanSource();
                        $imp_source->id_impact_identification = $manage[$i]['id'];
                     }
                     
                     $imp_source->description = $impact_source[$a]['description'];
                     $imp_source->save();
                 }
             }

             // === SUCCESS INDICATOR === //
             $success_indicator = $manage[$i]['success_indicator'];
             if(count($success_indicator) > 0) {
                 for($a = 0; $a < count($success_indicator); $a++) {
                     $imp_indicator = null;
                     if($success_indicator[$a]['id'] !== null) {
                         $imp_indicator = EnvPlanIndicator::findOrFail($success_indicator[$a]['id']);
                     } else {
                        $imp_indicator = new EnvPlanIndicator();
                        $imp_indicator->id_impact_identification = $manage[$i]['id'];
                     }
                     
                     $imp_indicator->description = $success_indicator[$a]['description'];
                     $imp_indicator->save();
                 }
             }

            // === FORM === //
            $form = $manage[$i]['form'];
            if(count($form) > 0) {
                for($a = 0; $a < count($form); $a++) {
                    $form_data = null;
                    if($form[$a]['id'] !== null) {
                        $form_data = EnvPlanForm::findOrFail($form[$a]['id']);
                    } else {
                       $form_data = new EnvPlanForm();
                       $form_data->id_env_manage_plan = $envManage->id;
                    }
                    
                    $form_data->description = $form[$a]['description'];
                    $form_data->save();
                }
            }

            // === LOCATION === //
            $location = $manage[$i]['location'];
            if(count($location) > 0) {
                for($a = 0; $a < count($location); $a++) {
                    $location_data = null;
                    if($location[$a]['id'] !== null) {
                        $location_data = EnvPlanLocation::findOrFail($location[$a]['id']);
                    } else {
                       $location_data = new EnvPlanLocation();
                       $location_data->id_env_manage_plan = $envManage->id;
                    }
                    
                    $location_data->description = $location[$a]['description'];
                    $location_data->save();
                }
            }
        }

        // === WORKFLOW === //
        $project = Project::findOrFail($request->idProject);
        if($project->marking == 'amdal.andal-drafting') {
            $project->workflow_apply('draft-amdal-rklrpl');
            $project->save();
        }

        if($request->type != 'new') {
            $lastTime = EnvManagePlan::whereIn('id_impact_identifications', $ids)->orderBy('updated_at', 'DESC')->first()
                            ->updated_at->locale('id')->diffForHumans();
            return 'Diperbarui ' . $lastTime;
        }

        return 'Diperbarui ' . EnvManagePlan::orderBy('id', 'DESC')->first()->updated_at->locale('id')->diffForHumans();
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

    private function checkWorkspace($id_project)
    {
        $count_1 = EnvManagePlan::whereHas('impactIdentification', function($q) use($id_project) {
            $q->where('id_project', $id_project);
        })
        ->count();

        if($count_1 == 0) {
            return false;
        }

        $count_2 = EnvMonitorPlan::whereHas('impactIdentification', function($q) use($id_project) {
            $q->where('id_project', $id_project);
        })
        ->count();

        if($count_2 == 0) {
            return false;
        }

        $count_1_2 = EnvManagePlan::whereHas('impactIdentification', function($q) use($id_project) {
            $q->where('id_project', $id_project);
        })
        ->where('period', '!=', null)
        ->count();

        if($count_1 !== $count_1_2) {
            return false;
        }

        $count_2_2 = EnvMonitorPlan::whereHas('impactIdentification', function($q) use($id_project) {
            $q->where('id_project', $id_project);
        })
        ->where('time_frequent', '!=', null)
        ->count();

        if($count_2 !== $count_2_2) {
            return false;
        }

        return true;
    }

    private function getEnvManagePlan($id_project, $stages, $type) {
        $results = [];

        // ============== POIN A ============== //
        $poinA = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
        ->where([['id_project', $id_project],['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis', function($q) {
            $q->whereHas('detail', function($query) {
                $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
            });
        })->get();
        $results[] = [
            'id' => 1,
            'no' => 'A. Dampak Penting Yang Dikelola (Hasil Arahan Pengelolaan pada ANDAL)',
            'type' => 'master-title'
         ];
         $results = $this->getLoopData($stages, $poinA, $results, $type);

        //  =========== POIN B ============= //
        $results[] = [
            'id' => 2,
            'no' => 'B. Dampak Lingkungan Lainnya yang Dikelola',
            'type' => 'master-title'
         ];

         $idPoinA = [];
         foreach($poinA as $pA) {
             $idPoinA[] = $pA->id;
         }
         $results = $this->getLoopDataB($stages, $id_project, $results, $type, $idPoinA);

        return $results;
  }

  private function getLoopData($stages, $data, $results, $type) {
    $alphabet_list = 'A';

    foreach($stages as $s) {
        $results[] = [
            'id' => $s->id,
            'name' => $alphabet_list . '. ' . $s->name,
            'type' => 'title'
        ];

        $total = 0;

        foreach($data as $pA) {
            $ronaAwal = '';
            $component = '';
            $periodeNumber = '0';
            $periodeDesc = '';

            // check stages
            $id_stages = null;

            if($pA->projectComponent) {
                $id_stages = $pA->projectComponent->component->id_project_stage;
    
                if($id_stages == $s->id) {
                    if($pA->projectRonaAwal) {
                        $ronaAwal = $pA->projectRonaAwal->rona_awal->name;
                        $component = $pA->projectComponent->component->name;
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            } else {
                continue;
            }

            $changeType = $pA->id_change_type ? $pA->changeType->name : '';

           $comments = $this->getComments($pA->id);

           
           if(!$pA->envManagePlan) {
               $type = 'new';
            }
            
            // PERIODE NUMBER & DESCRIPTION
            if($type != 'new') {
                if($pA->envManagePlan->period) {
                    $split_period = explode('-', $pA->envManagePlan->period);
                    if(is_numeric($split_period[0])) {
                        $periodeNumber = $split_period[0];
                    }
                    if(count($split_period) > 1) {
                        $periodeDesc = $split_period[1];
                    }
                }
            }

            // === INSTITUTION === //
            $institution = EnvPlanInstitution::where('id_impact_identification', $pA->id)->first();

            // === IMPACT SOURCE === //
            $impact_source = EnvPlanSource::select('id', 'description', 'id_impact_identification')->where('id_impact_identification', $pA->id)->get();

            // === SUCCESS INDICATOR == //
            $success_indicator = EnvPlanIndicator::select('id', 'description', 'id_impact_identification')->where('id_impact_identification', $pA->id)->get();

            $results[] = [
                'no' => $total + 1,
                'id' => $pA->id,
                'name' => "$changeType $ronaAwal akibat $component",
                'type' => 'subtitle',
                'impact_source' => $impact_source,
                'success_indicator' => $success_indicator,
                'form' => 
                    $type == 'new' ? 
                    [] : 
                    EnvPlanForm::select('id', 'description', 'id_env_manage_plan')
                                      ->where('id_env_manage_plan', $pA->envManagePlan->id)
                                      ->get(),
                'location' => 
                    $type == 'new' ? 
                    [] : 
                    EnvPlanLocation::select('id', 'description', 'id_env_manage_plan')
                                      ->where('id_env_manage_plan', $pA->envManagePlan->id)
                                      ->get(),
                'period' => $type == 'new' ? null : $pA->envManagePlan->period,
                'executor' => $institution ? $institution->executor : null,
                'supervisor' => $institution ? $institution->supervisor : null,
                'report_recipient' => $institution ? $institution->report_recipient : null,
                'period_number' => $periodeNumber,
                'period_description' => $periodeDesc,
                'comments' => $comments
            ];
            $results[] = [
                'id' => $pA->id,
                'type' => 'comments'
            ];
            $total++;
        }

        if($total == 0) {
            array_pop($results);
        } else {
            $alphabet_list++;
        }
    }

    return $results;
  }

  private function getLoopDataB($stages, $id_project, $results, $type, $idPoinA) {
    //  =========== POIN B.1 ============= //
    // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
    $poinB1 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
        ->where([['id_project', $id_project],['is_managed', true], ['is_hypothetical_significant', '!=', true]])->get();

    //  =========== POIN B.2 ============= //
    // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
    $poinB2 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
        ->where([['id_project', $id_project],['is_managed', false],['initial_study_plan', '!=', null], ['is_hypothetical_significant', '!=', true]])->get();

    // ============ POIN B.3 ============= //
    // DAMPAK TIDAK PENTING (HASIL MATRIK ANDAL (TP))
    if(count($idPoinA) > 0) {
        $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
        ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->whereNotIn('id', $idPoinA)->whereHas('envImpactAnalysis')->get();
    } else {
        $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
        ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis')->get();
    }

    $data_merge_1 = $poinB1->merge($poinB2);
    $data_merge_final = $data_merge_1->merge($poinB3);

    $alphabet_list = 'A';

    foreach($stages as $s) {
        $results[] = [
            'id' => $s->id,
            'name' => $alphabet_list . '. ' . $s->name,
            'type' => 'title'
        ];

        $total = 0;

        foreach($data_merge_final as $merge) {
            $ronaAwal = '';
            $component = '';
            $periodeNumber = '0';
            $periodeDesc = '';

            // check stages
            $id_stages = null;

            if($merge->projectComponent) {
                $id_stages = $merge->projectComponent->component->id_project_stage;
    
                if($id_stages == $s->id) {
                    if($merge->projectRonaAwal) {
                        $ronaAwal = $merge->projectRonaAwal->rona_awal->name;
                        $component = $merge->projectComponent->component->name;
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            } else {
                continue;
            }

            $changeType = $merge->id_change_type ? $merge->changeType->name : '';

            $comments = $this->getComments($merge->id);

            if(!$merge->envManagePlan) {
                $type = 'new';
            }

             // PERIODE NUMBER & DESCRIPTION
             if($type != 'new') {
                if($merge->envManagePlan->period) {
                    $split_period = explode('-', $merge->envManagePlan->period);
                    if(is_numeric($split_period[0])) {
                        $periodeNumber = $split_period[0];
                    }
                    if(count($split_period) > 1) {
                        $periodeDesc = $split_period[1];
                    }
                }
            }

            // === INSTITUTION === //
            $institution = EnvPlanInstitution::where('id_impact_identification', $merge->id)->first();

             // === IMPACT SOURCE === //
             $impact_source = EnvPlanSource::select('id', 'description', 'id_impact_identification')->where('id_impact_identification', $merge->id)->get();

             // === SUCCESS INDICATOR == //
             $success_indicator = EnvPlanIndicator::select('id', 'description', 'id_impact_identification')->where('id_impact_identification', $merge->id)->get();

            $results[] = [
                'no' => $total + 1,
                'id' => $merge->id,
                'name' => "$changeType $ronaAwal akibat $component",
                'type' => 'subtitle',
                'impact_source' => $impact_source,
                'success_indicator' => $success_indicator,
                'form' => 
                    $type == 'new' ? 
                    [] : 
                    EnvPlanForm::select('id', 'description', 'id_env_manage_plan')
                                      ->where('id_env_manage_plan', $merge->envManagePlan->id)
                                      ->get(),
                'location' => 
                    $type == 'new' ? 
                    [] : 
                    EnvPlanLocation::select('id', 'description', 'id_env_manage_plan')
                                      ->where('id_env_manage_plan', $merge->envManagePlan->id)
                                      ->get(),
                'period' => $type == 'new' ? null : $merge->envManagePlan->period,
                'executor' => $institution ? $institution->executor : null,
                'supervisor' => $institution ? $institution->supervisor : null,
                'report_recipient' => $institution ? $institution->report_recipient : null,
                'period_number' => $periodeNumber,
                'period_description' => $periodeDesc,
                'comments' => $comments
            ];
            $results[] = [
                'id' => $merge->id,
                'type' => 'comments'
            ];
            $total++;
        }

        if($total == 0) {
            array_pop($results);
        } else {
            $alphabet_list++;
        }
    }

    return $results;
  }

  private function getPoinA($stages, $id_project, $impact_source, $indicator, $institution) {
    $results = []; 

    $poinA = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
    ->where([['id_project', $id_project],['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis', function($q) {
        $q->whereHas('detail', function($query) {
            $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
        });
    })->get();

    $alphabet_list = 'A';

    $idx = 0;
    foreach($stages as $s) {
        $results['a_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl'] = [];

        $total = 0;

        foreach($poinA as $pA) {
            $ronaAwal = '';
            $component = '';

            $data = $this->getComponentRonaAwal($pA, $s->id);

            if($data['component'] && $data['ronaAwal']) {
                $ronaAwal = $data['ronaAwal'];   
                $component = $data['component'];   
            } else {
                continue;
            }

            $changeType = $pA->id_change_type ? $pA->changeType->name : '';

            $institution_data = $institution->where('id_impact_identification', $pA->id)->first();

            if($pA->envManagePlan) {
                $results['a_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl'][] = [
                    'a_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl' => $total + 1,
                    'id' => $pA->id,
                    'name' => "$changeType $ronaAwal akibat $component",
                    'type' => 'subtitle',
                    'impact_source' => $this->getImpactSourceList($impact_source, $pA->id),
                    'success_indicator' => $this->getIndicatorList($indicator, $pA->id),
                    'form' => $this->getForm('id_env_manage_plan', $pA->envManagePlan->id),
                    'location' => $this->getLocation('id_env_manage_plan', $pA->envManagePlan->id),
                    'period' => $pA->envManagePlan->period,
                    'executor' => $institution_data ? $institution_data->executor : '',
                    'supervisor' => $institution_data ? $institution_data->supervisor : '',
                    'report_recipient' => $institution_data ? $institution_data->report_recipient : '',
                ];
                $total++;
            } else {
                continue;
            }
        }
        $idx++;
    }

    return $results;
    
  }

    private function getPoinB($stages, $id_project, $impact_source, $indicator, $institution) {
        $results = [];

       //  =========== POIN B.1 ============= //
        // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
        $poinB1 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
        ->where([['id_project', $id_project],['is_managed', true],['is_hypothetical_significant', '!=', true]])->get();

        //  =========== POIN B.2 ============= //
        // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
        $poinB2 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
            ->where([['id_project', $id_project],['is_managed', false],['initial_study_plan', '!=', null],['is_hypothetical_significant', '!=', true]])->get();

        // ============ POIN B.3 ============= //
        // DAMPAK TIDAK PENTING (HASIL MATRIK ANDAL (TP))
        $idPoinA = [];
        $poinA = ImpactIdentificationClone::select('id', 'id_project', 'is_hypothetical_significant')
        ->where([['id_project', $id_project],['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis', function($q) {
            $q->whereHas('detail', function($query) {
                $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
            });
        })->get();

        foreach($poinA as $pA) {
            $idPoinA[] = $pA->id;
        }

        if(count($idPoinA) > 0) {
            $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
            ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->whereNotIn('id', $idPoinA)->whereHas('envImpactAnalysis')->get();
        } else {
            $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
            ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis')->get();
        }

        $data_merge_1 = $poinB1->merge($poinB2);
        $data_merge_final = $data_merge_1->merge($poinB3);
    
        $alphabet_list = 'A';
    
        $idx = 0;
        foreach($stages as $s) {
            $results['b_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl'] = [];
    
            $total = 0;
    
            foreach($data_merge_final as $merge) {
                $ronaAwal = '';
                $component = '';

                $data = $this->getComponentRonaAwal($merge, $s->id);

                if($data['component'] && $data['ronaAwal']) {
                    $ronaAwal = $data['ronaAwal'];   
                    $component = $data['component'];   
                } else {
                    continue;
                }
                $changeType = $merge->id_change_type ? $merge->changeType->name : '';
                $ronaAwal =  $merge->projectRonaAwal->rona_awal->name;
                $component = $merge->projectComponent->component->name;

                $institution_data = $institution->where('id_impact_identification', $merge->id)->first();

                if($merge->envManagePlan) {
                    $results['b_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl'][] = [
                        'b_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl'  => $total + 1,
                        'id' => $merge->id,
                        'name' => "$changeType $ronaAwal akibat $component",
                        'type' => 'subtitle',
                        'impact_source' => $this->getImpactSourceList($impact_source, $merge->id),
                        'success_indicator' => $this->getIndicatorList($indicator, $merge->id),
                        'form' => $this->getForm('id_env_manage_plan', $merge->envManagePlan->id),
                        'location' => $this->getLocation('id_env_manage_plan', $merge->envManagePlan->id),
                        'period' => $merge->envManagePlan->period,
                        'executor' => $institution_data ? $institution_data->executor : '',
                        'supervisor' => $institution_data ? $institution_data->supervisor : '',
                        'report_recipient' => $institution_data ? $institution_data->report_recipient : '',
                    ];
                } else {
                    continue;
                }
                $total++;
            }
            $idx++;
        }
    
        return $results;
    }

    private function getPoinARpl($stages, $id_project, $impact_source, $indicator, $institution) {
        $results = [];

        $poinA = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
        ->where([['id_project', $id_project],['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis', function($q) {
            $q->whereHas('detail', function($query) {
                $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
            });
        })->get();

        $alphabet_list = 'A';

        $idx = 0;
        foreach($stages as $s) {
            $results['a_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl'] = [];

            $total = 0;

            foreach($poinA as $pA) {
                $ronaAwal = '';
                $component = '';

                $data = $this->getComponentRonaAwal($pA, $s->id);

                if($data['component'] && $data['ronaAwal']) {
                    $ronaAwal = $data['ronaAwal'];   
                    $component = $data['component'];   
                } else {
                    continue;
                }
                $changeType = $pA->id_change_type ? $pA->changeType->name : '';

                $institution_data = $institution->where('id_impact_identification', $pA->id)->first();

                if($pA->envMonitorPlan) {
                    $results['a_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl'][] = [
                        'a_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl' => $total + 1,
                        'id' => $pA->id,
                        'name' => "$changeType $ronaAwal akibat $component",
                        'type' => 'subtitle',
                        'indicator' => $this->getIndicatorList($indicator, $pA->id),
                        'impact_source' => $this->getImpactSourceList($impact_source, $pA->id),
                        'collection_method' => $this->getForm('id_env_monitor_plan', $pA->envMonitorPlan->id),
                        'location' => $this->getLocation('id_env_monitor_plan', $pA->envMonitorPlan->id),
                        'time_frequent' => $pA->envMonitorPlan->time_frequent,
                        'executor' => $institution_data ? $institution_data->executor : '',
                        'supervisor' => $institution_data ? $institution_data->supervisor : '',
                        'report_recipient' => $institution_data ? $institution_data->report_recipient : '',
                        'description' => $pA->envMonitorPlan->description,
                    ];
                } else {
                    continue;
                }
                $total++;
            }
            $idx++;
        }

        return $results;
    }

    private function getPoinBRpl($stages, $id_project, $impact_source, $indicator, $institution) {
       $results = [];

        //  =========== POIN B.1 ============= //
       // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
       $poinB1 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
       ->where([['id_project', $id_project],['is_managed', true],['is_hypothetical_significant', '!=', true]])->get();

       //  =========== POIN B.2 ============= //
       // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
       $poinB2 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
           ->where([['id_project', $id_project],['is_managed', false],['initial_study_plan', '!=', null],['is_hypothetical_significant', '!=', true]])->get();

       // ============ POIN B.3 ============= //
       // DAMPAK TIDAK PENTING (HASIL MATRIK ANDAL (TP))
       $idPoinA = [];
       $poinA = ImpactIdentificationClone::select('id', 'id_project', 'is_hypothetical_significant')
       ->where([['id_project', $id_project],['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis', function($q) {
           $q->whereHas('detail', function($query) {
               $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
           });
       })->get();

       foreach($poinA as $pA) {
           $idPoinA[] = $pA->id;
       }

       if(count($idPoinA) > 0) {
           $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
           ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->whereNotIn('id', $idPoinA)->whereHas('envImpactAnalysis')->get();
       } else {
        $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
        ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis')->get();
       }

       $data_merge_1 = $poinB1->merge($poinB2);
       $data_merge_final = $data_merge_1->merge($poinB3);

       $alphabet_list = 'A';

       $idx = 0;
       foreach($stages as $s) {
        $results['b_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl'] = [];

           $total = 0;

           foreach($data_merge_final as $merge) {
                $ronaAwal = '';
                $component = '';

                $data = $this->getComponentRonaAwal($merge, $s->id);

                if($data['component'] && $data['ronaAwal']) {
                    $ronaAwal = $data['ronaAwal'];   
                    $component = $data['component'];   
                } else {
                    continue;
                }
                
                $changeType = $merge->id_change_type ? $merge->changeType->name : '';

                $institution_data = $institution->where('id_impact_identification', $merge->id)->first();

                if($merge->envMonitorPlan) {
                    $results['b_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl'][] = [
                        'b_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl' => $total + 1,
                        'id' => $merge->id,
                        'name' => "$changeType $ronaAwal akibat $component",
                        'type' => 'subtitle',
                        'indicator' => $this->getIndicatorList($indicator, $merge->id),
                        'impact_source' => $this->getImpactSourceList($impact_source, $merge->id),
                        'collection_method' => $this->getForm('id_env_monitor_plan', $merge->envMonitorPlan->id),
                        'location' => $this->getLocation('id_env_monitor_plan', $merge->envMonitorPlan->id),
                        'time_frequent' => $merge->envMonitorPlan->time_frequent,
                        'executor' => $institution_data ? $institution_data->executor : '',
                        'supervisor' => $institution_data ? $institution_data->supervisor : '',
                        'report_recipient' => $institution_data ? $institution_data->report_recipient : '',
                        'description' => $merge->envMonitorPlan->description,
                    ];
                } else {
                    continue;
                }
                $total++;
           }
           $idx++;
       }

       return $results;
    }

    private function getComponentRonaAwal($imp, $id_project_stage) {
        $component = null;
        $ronaAwal = null;

        try {
            if ($imp->projectComponent) {
                if ($imp->projectComponent->component->id_project_stage == $id_project_stage) {
                    if ($imp->projectRonaAwal) {
                        $ronaAwal = $imp->projectRonaAwal->rona_awal->name;
                        $component = $imp->projectComponent->component->name;
                    }
                }
                // } else if($imp->subProjectComponent->id_project_stage != null) {
                //     if(($imp->subProjectComponent->component) && imp->subProjectComponent->component->id_project_stage == $id_project_stage) {
                //         if($imp->subProjectRonaAwal) {
                //             $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                //             $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                //         }
                //     }
                // }
            }
        } catch(ErrorException $err) {
            
        }

        return [
            'component' => $component,
            'ronaAwal' => $ronaAwal
        ];
    }

    private function getComments($id) {
        $komen = Comment::where([['id_impact_identification', $id], ['document_type', 'rkl'],['reply_to', null]])
                        ->orderBY('id', 'DESC')->get();
            
        $comments = [];
        foreach($komen as $c) {
            $replies = [];

            if($c->reply) {
                foreach($c->reply as $r) {
                    $replies[] = [
                        'id' => $r->id,
                        'created_at' => $r->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                        'description' => $r->description
                    ];
                }
            }

            $comments[] = [
                'id' => $c->id,
                'id_impact_identification' => $c->id_impact_identification,
                'created_at' => $c->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                'user' => $c->user->name,
                'is_checked' => $c->is_checked,
                'description' => $c->description,
                'column_type' => $c->column_type,
                'replies' => $replies
            ];
        }

        return $comments;
    }

    private function getImpactSourceList($impact_source, $id)
    {
        $imp_source = '';
        $total_source = 1;
        $sources = $impact_source->where('id_impact_identification', $id);
        foreach($sources as $s) {
            $imp_source .= $total_source . '. ' . $s->description . '</w:t><w:p/><w:t>';
            $total_source++;
        }

        return $imp_source;
    }

    private function getIndicatorList($indicator, $id)
    {
        $indicator_data = '';
        $total = 1;
        $indicators = $indicator->where('id_impact_identification', $id);
        foreach($indicators as $i) {
            $indicator_data .= $total . '. ' . $i->description . '</w:t><w:p/><w:t>';
            $total++;
        }

        return $indicator_data;
    }

    private function getForm($type_id, $id)
    {
        $form = '';

        $imp_form = EnvPlanForm::where($type_id, $id)->get();

        $total = 1;
        foreach($imp_form as $i) {
            $form .= $total . '. ' . $i->description . '</w:t><w:p/><w:t>';
            $total++;
        }

        return $form;
    }

    private function getLocation($type_id, $id)
    {
        $location = '';

        $locations = EnvPlanLocation::where($type_id, $id)->get();

        $total = 1;
        foreach($locations as $l) {
            $location .= $total . '. ' . $l->description . '</w:t><w:p/><w:t>';
            $total++;
        }

        return $location ;
    }
}
