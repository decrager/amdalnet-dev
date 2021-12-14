<?php

namespace App\Http\Controllers;

use App\Entity\Comment;
use App\Entity\EnvImpactAnalysis;
use App\Entity\EnvManagePlan;
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

class MatriksRKLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->docs) {
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

            $poinA = $this->getPoinA($stages, $request->idProject);
            $poinB = $this->getPoinB($stages, $request->idProject);

            $poinAp = $this->getPoinARpl($stages, $request->idProject);
            $poinBp = $this->getPoinBRpl($stages, $request->idProject);

            $templateProcessor->setValue('project_title', $project->project_title);
            $templateProcessor->setValue('district', $project_district);
            $templateProcessor->setValue('province', $project_province);
            $templateProcessor->setValue('pemrakarsa', $project->initiator->name);

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

            $save_file_name = $request->idProject .'-rkl-rpl' . '.docx'; 
            if (!File::exists(storage_path('app/public/workspace/'))) {
                File::makeDirectory(storage_path('app/public/workspace/'));
            }

            if (!File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
                $templateProcessor->saveAs(storage_path('app/public/workspace/' . $save_file_name));
            }
            

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
                    $ids[] = $manage[$i]['id'];
                }

                $envManage->impact_source = $manage[$i]['impact_source'];
                $envManage->success_indicator = $manage[$i]['success_indicator'];
                $envManage->form = $manage[$i]['form'];
                $envManage->location = $manage[$i]['location'];
                $envManage->period = $manage[$i]['period'];
                $envManage->institution = $manage[$i]['institution'];
                $envManage->save();
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

    private function getEnvManagePlan($id_project, $stages, $type) {
        $results = [];

        // ============== POIN A ============== //
        $poinA = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where('id_project', $id_project)->whereHas('envImpactAnalysis', function($q) {
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

        
         $results = $this->getLoopDataB($stages, $id_project, $results, $type);

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

            // check stages
            $id_stages = null;

            if($pA->subProjectComponent) {
                if($pA->subProjectComponent->id_project_stage) {
                    $id_stages = $pA->subProjectComponent->id_project_stage;
                } else {
                    $id_stages = $pA->subProjectComponent->component->id_project_stage;
                }
    
                if($id_stages == $s->id) {
                    if($pA->subProjectRonaAwal) {
                        $ronaAwal = $pA->subProjectRonaAwal->id_rona_awal ? $pA->subProjectRonaAwal->ronaAwal->name : $pA->subProjectRonaAwal->name;
                        $component = $pA->subProjectComponent->id_component ? $pA->subProjectComponent->component->name : $pA->subProjectComponent->name;
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

            $results[] = [
                'no' => $total + 1,
                'id' => $pA->id,
                'name' => "$changeType $ronaAwal akibat $component",
                'type' => 'subtitle',
                'impact_source' => $type == 'new' ? null : $pA->envManagePlan->impact_source,
                'success_indicator' => $type == 'new' ? null : $pA->envManagePlan->success_indicator,
                'form' => $type == 'new' ? null : $pA->envManagePlan->form,
                'location' => $type == 'new' ? null : $pA->envManagePlan->location,
                'period' => $type == 'new' ? null : $pA->envManagePlan->period,
                'institution' => $type == 'new' ? null : $pA->envManagePlan->institution,
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

  private function getLoopDataB($stages, $id_project, $results, $type) {
    //  =========== POIN B.1 ============= //
    // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
    $poinB1 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where([['id_project', $id_project],['is_managed', true]])->get();

    //  =========== POIN B.2 ============= //
    // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
    $poinB2 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where([['id_project', $id_project],['is_managed', false],['initial_study_plan', '!=', null]])->get();

    // ============ POIN B.3 ============= //
    // DAMPAK TIDAK PENTING (HASIL MATRIK ANDAL (TP))
    $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
    ->where('id_project', $id_project)->whereHas('envImpactAnalysis', function($q) {
        $q->whereDoesntHave('detail', function($query) {
            $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
        });
    })->get();

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

            // check stages
            $id_stages = null;

            if($merge->subProjectComponent) {
                if($merge->subProjectComponent->id_project_stage) {
                    $id_stages = $merge->subProjectComponent->id_project_stage;
                } else {
                    $id_stages = $merge->subProjectComponent->component->id_project_stage;
                }
    
                if($id_stages == $s->id) {
                    if($merge->subProjectRonaAwal) {
                        $ronaAwal = $merge->subProjectRonaAwal->id_rona_awal ? $merge->subProjectRonaAwal->ronaAwal->name : $merge->subProjectRonaAwal->name;
                        $component = $merge->subProjectComponent->id_component ? $merge->subProjectComponent->component->name : $merge->subProjectComponent->name;
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

            $results[] = [
                'no' => $total + 1,
                'id' => $merge->id,
                'name' => "$changeType $ronaAwal akibat $component",
                'type' => 'subtitle',
                'impact_source' => $type == 'new' ? null : $merge->envManagePlan->impact_source,
                'success_indicator' => $type == 'new' ? null : $merge->envManagePlan->success_indicator,
                'form' => $type == 'new' ? null : $merge->envManagePlan->form,
                'location' => $type == 'new' ? null : $merge->envManagePlan->location,
                'period' => $type == 'new' ? null : $merge->envManagePlan->period,
                'institution' => $type == 'new' ? null : $merge->envManagePlan->institution,
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

  private function getPoinA($stages, $id_project) {
    $results = []; 

    $poinA = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
    ->where('id_project', $id_project)->whereHas('envImpactAnalysis', function($q) {
        $q->whereHas('detail', function($query) {
            $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
        });
    })->get();

    $alphabet_list = 'A';

    $idx = 0;
    foreach($stages as $s) {
        // $results[$idx]['stages'] = $alphabet_list . '. ' . $s->name;
        // $results[$idx]['data'] = [];
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

            if($pA->envManagePlan) {
                $results['a_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl'][] = [
                    'a_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl' => $total + 1,
                    'id' => $pA->id,
                    'name' => "$changeType $ronaAwal akibat $component",
                    'type' => 'subtitle',
                    'impact_source' => $pA->envManagePlan->impact_source,
                    'success_indicator' => $pA->envManagePlan->success_indicator,
                    'form' => $pA->envManagePlan->form,
                    'location' => $pA->envManagePlan->location,
                    'period' => $pA->envManagePlan->period,
                    'institution' => $pA->envManagePlan->institution,
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

    private function getPoinB($stages, $id_project) {
        $results = [];

       //  =========== POIN B.1 ============= //
        // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
        $poinB1 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where([['id_project', $id_project],['is_managed', true]])->get();

        //  =========== POIN B.2 ============= //
        // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
        $poinB2 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
            ->where([['id_project', $id_project],['is_managed', false],['initial_study_plan', '!=', null]])->get();

        // ============ POIN B.3 ============= //
        // DAMPAK TIDAK PENTING (HASIL MATRIK ANDAL (TP))
        $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where('id_project', $id_project)->whereHas('envImpactAnalysis', function($q) {
            $q->whereDoesntHave('detail', function($query) {
                $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
            });
        })->get();

        $data_merge_1 = $poinB1->merge($poinB2);
        $data_merge_final = $data_merge_1->merge($poinB3);
    
        $alphabet_list = 'A';
    
        $idx = 0;
        foreach($stages as $s) {
            // $results[$idx]['stages'] = $alphabet_list . '. ' . $s->name;
            // $results[$idx]['data'] = [];
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
                // $ronaAwal =  $merge->ronaAwal->id_rona_awal ? $merge->ronaAwal->rona_awal->name : $merge->ronaAwal->name;
                // $component = $merge->component->id_component ? $merge->component->component->name : $merge->component->name;
                $ronaAwal =  $merge->subProjectRonaAwal->id_rona_awal ? $merge->subProjectRonaAwal->ronaAwal->name : $merge->subProjectRonaAwal->name;
                $component = $merge->subProjectComponent->id_component ? $merge->subProjectComponent->component->name : $merge->subProjectComponent->name;

                if($merge->envManagePlan) {
                    $results['b_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl'][] = [
                        'b_' . str_replace(' ', '_', strtolower($s->name)) . '_rkl'  => $total + 1,
                        'id' => $merge->id,
                        'name' => "$changeType $ronaAwal akibat $component",
                        'type' => 'subtitle',
                        'impact_source' => $merge->envManagePlan->impact_source,
                        'success_indicator' => $merge->envManagePlan->success_indicator,
                        'form' => $merge->envManagePlan->form,
                        'location' => $merge->envManagePlan->location,
                        'period' => $merge->envManagePlan->period,
                        'institution' => $merge->envManagePlan->institution,
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

    private function getPoinARpl($stages, $id_project) {
        $results = [];

        $poinA = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where('id_project', $id_project)->whereHas('envImpactAnalysis', function($q) {
            $q->whereHas('detail', function($query) {
                $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
            });
        })->get();

        $alphabet_list = 'A';

        $idx = 0;
        foreach($stages as $s) {
            // $results[$idx]['stages'] =  $alphabet_list . '. ' . $s->name;
            // $results[$idx]['data'] = [];
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

                if($pA->envMonitorPlan) {
                    $results['a_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl'][] = [
                        'a_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl' => $total + 1,
                        'id' => $pA->id,
                        'name' => "$changeType $ronaAwal akibat $component",
                        'type' => 'subtitle',
                        'indicator' => $pA->envMonitorPlan->indicator,
                        'impact_source' => $pA->envMonitorPlan->impact_source,
                        'collection_method' => $pA->envMonitorPlan->collection_method,
                        'location' => $pA->envMonitorPlan->location,
                        'time_frequent' => $pA->envMonitorPlan->time_frequent,
                        'executor' => $pA->envMonitorPlan->executor,
                        'supervisor' => $pA->envMonitorPlan->supervisor,
                        'report_recipient' => $pA->envMonitorPlan->report_recipient,
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

    private function getPoinBRpl($stages, $id_project) {
       $results = [];

        //  =========== POIN B.1 ============= //
       // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
       $poinB1 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
       ->where([['id_project', $id_project],['is_managed', true]])->get();

       //  =========== POIN B.2 ============= //
       // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
       $poinB2 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
           ->where([['id_project', $id_project],['is_managed', false],['initial_study_plan', '!=', null]])->get();

       // ============ POIN B.3 ============= //
       // DAMPAK TIDAK PENTING (HASIL MATRIK ANDAL (TP))
       $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
       ->where('id_project', $id_project)->whereHas('envImpactAnalysis', function($q) {
           $q->whereDoesntHave('detail', function($query) {
               $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
           });
       })->get();

       $data_merge_1 = $poinB1->merge($poinB2);
       $data_merge_final = $data_merge_1->merge($poinB3);

       $alphabet_list = 'A';

       $idx = 0;
       foreach($stages as $s) {
        //    $results[$idx]['stages'] =  $alphabet_list . '. ' . $s->name;
        //    $results[$idx]['data'] = [];
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

                if($merge->envMonitorPlan) {
                    $results['b_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl'][] = [
                        'b_' . str_replace(' ', '_', strtolower($s->name)) . '_rpl' => $total + 1,
                        'id' => $merge->id,
                        'name' => "$changeType $ronaAwal akibat $component",
                        'type' => 'subtitle',
                        'indicator' => $merge->envMonitorPlan->indicator,
                        'impact_source' => $merge->envMonitorPlan->impact_source,
                        'collection_method' => $merge->envMonitorPlan->collection_method,
                        'location' => $merge->envMonitorPlan->location,
                        'time_frequent' => $merge->envMonitorPlan->time_frequent,
                        'executor' => $merge->envMonitorPlan->executor,
                        'supervisor' => $merge->envMonitorPlan->supervisor,
                        'report_recipient' => $merge->envMonitorPlan->report_recipient,
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
            if($imp->subProjectComponent) {
                if($imp->subProjectComponent->id_project_stage == $id_project_stage) {
                    if($imp->subProjectRonaAwal) {
                        $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                        $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                    }
                } else if($imp->subProjectComponent->id_project_stage != null) {
                    if(($imp->subProjectComponent->component) && imp->subProjectComponent->component->id_project_stage == $id_project_stage) {
                        if($imp->subProjectRonaAwal) {
                            $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                            $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                        }
                    }
                }
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
}
