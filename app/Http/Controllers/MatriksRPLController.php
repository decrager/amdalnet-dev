<?php

namespace App\Http\Controllers;

use App\Entity\EnvImpactAnalysis;
use App\Entity\EnvManagePlan;
use App\Entity\Comment;
use App\Entity\EnvMonitorPlan;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactIdentificationClone;
use App\Entity\Institution;
use App\Entity\Project;
use App\Entity\ProjectStage;
use ErrorException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MatriksRPLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->docs) {
            $poinA = [];
            $poinB = [];

            $ids = [4,1,2,3];
            $stages = ProjectStage::select('id', 'name')->get()->sortBy(function($model) use($ids) {
                return array_search($model->getKey(),$ids);
            });

            $isExist = Project::where('id', $request->idProject)->whereHas('impactIdentificationsClone', function($query) {
                $query->whereHas('envMonitorPlan');
            })->first();

            if($isExist) {
                $poinA = $this->getPoinA($stages, $request->idProject);
                $poinB = $this->getPoinB($stages, $request->idProject);
            }

            return [
                'poinA' => $poinA,
                'poinB' => $poinB
            ];
        }

        if($request->institution) {
            return Institution::all();
        }

        if($request->project) {
            return Project::whereHas('impactIdentifications', function($q) {
                $q->whereHas('envImpactAnalysis');
            })->get();
        }

        if($request->lastTime && $request->idProject) {
            $id_project = $request->idProject;
            $time = EnvMonitorPlan::whereHas('impactIdentification', function($q) use($id_project) {
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
                $query->whereHas('envMonitorPlan');
            })->first();

            $type = $project ? 'update' : 'new';

            return $this->getEnvMonitorPlan($request->idProject, $stages, $type);
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
                $project->map_rpl = Storage::url($name);
                $project->save();
   
                return response()->json(['message' => 'success']);
           }
   
           return response()->json(['message' => 'failed'], 404);
        }

        if($request->type == 'impact-comment') {
            $comment = new Comment();
            $comment->id_user = $request->id_user;
            $comment->id_impact_identification = $request->id_impact_identification;
            $comment->description = $request->description;
            $comment->column_type = $request->column_type;
            $comment->document_type = 'rpl';
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
            $comment->document_type = 'rpl';
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

        $monitor = $request->monitor;
        $envMonitor = null;
        $ids = [];

        for($i = 0; $i < count($monitor); $i++) {
            if($request->type == 'new') {
                $envMonitor = new EnvMonitorPlan();
                $envMonitor->id_impact_identifications = $monitor[$i]['id'];
            } else {
                $envMonitor = EnvMonitorPlan::where('id_impact_identifications', $monitor[$i]['id'])->first();
                $ids[] = $monitor[$i]['id'];
            }

            $envMonitor->indicator = $monitor[$i]['indicator'];
            $envMonitor->impact_source = $monitor[$i]['impact_source'];
            $envMonitor->collection_method = $monitor[$i]['collection_method'];
            $envMonitor->location = $monitor[$i]['location'];
            $envMonitor->time_frequent = $monitor[$i]['period_number'] . '-' . $monitor[$i]['period_description'];
            $envMonitor->executor = $monitor[$i]['executor'];
            $envMonitor->supervisor = $monitor[$i]['supervisor'];
            $envMonitor->report_recipient = $monitor[$i]['report_recipient'];
            $envMonitor->save();
        }
        
        if($request->type != 'new') {
            $lastTime = EnvMonitorPlan::whereIn('id_impact_identifications', $ids)->orderBy('updated_at', 'DESC')->first()
                            ->updated_at->locale('id')->diffForHumans();
            return 'Diperbarui ' . $lastTime;
        }       

        return 'Diperbarui ' . EnvMonitorPlan::orderBy('id', 'DESC')->first()->updated_at->locale('id')->diffForHumans();
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

    private function getEnvMonitorPlan($id_project, $stages, $type) {
        $results = [];

        // ================== POIN A ================= //
        $poinA = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where([['id_project', $id_project],['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis', function($q) {
            $q->whereHas('detail', function($query) {
                $query->where('important_trait', '+P')->orWhere('important_trait', '-P');
            });
        })->get();
        $results[] = [
            'id' => 1,
            'no' => 'A. Dampak Penting Yang Dipantau (Hasil Arahan Pengelolaan pada ANDAL)',
            'type' => 'master-title'
         ];
        $results = $this->getLoopData($stages, $poinA, $results, $type);

        //  =========== POIN B ============= //
        $results[] = [
            'id' => 2,
            'no' => 'B. Dampak Lingkungan Lainnya yang Dipantau',
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

                if(!$pA->envMonitorPlan) {
                    $type = 'new';
                }

                // PERIODE NUMBER & DESCRIPTION
                if($type != 'new') {
                    if($pA->envMonitorPlan->time_frequent) {
                        $split_period = explode('-', $pA->envMonitorPlan->time_frequent);
                        if(is_numeric($split_period[0])) {
                            $periodeNumber = $split_period[0];
                        }
                        if(count($split_period) > 1) {
                            $periodeDesc = $split_period[1];
                        }
                    }
                }

                $results[] = [
                    'no' => $total + 1,
                    'id' => $pA->id,
                    'name' => "$changeType $ronaAwal akibat $component",
                    'type' => 'subtitle',
                    'indicator' => $type == 'new' ? null : $pA->envMonitorPlan->indicator,
                    'impact_source' => $type == 'new' ? null : $pA->envMonitorPlan->impact_source,
                    'collection_method' => $type == 'new' ? null : $pA->envMonitorPlan->collection_method,
                    'location' => $type == 'new' ? null : $pA->envMonitorPlan->location,
                    'time_frequent' => $type == 'new' ? null : $pA->envMonitorPlan->time_frequent,
                    'executor' => $type == 'new' ? null : $pA->envMonitorPlan->executor,
                    'supervisor' => $type == 'new' ? null : $pA->envMonitorPlan->supervisor,
                    'report_recipient' => $type == 'new' ? null : $pA->envMonitorPlan->report_recipient,
                    'description' => $type == 'new' ? null : $pA->envMonitorPlan->description,
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
        $poinB1 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where([['id_project', $id_project],['is_managed', true],['is_hypothetical_significant', '!=', true]])->get();

        //  =========== POIN B.2 ============= //
        // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
        $poinB2 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
            ->where([['id_project', $id_project],['is_managed', false],['initial_study_plan', '!=', null],['is_hypothetical_significant', '!=', true]])->get();

        // ============ POIN B.3 ============= //
        // DAMPAK TIDAK PENTING (HASIL MATRIK ANDAL (TP))
        if(count($idPoinA) > 0) {
            $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
            ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->whereNotIn('id', $idPoinA)->whereHas('envImpactAnalysis')->get();
        } else {
            $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
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

                if(!$merge->envMonitorPlan) {
                    $type = 'new';
                }

                // PERIODE NUMBER & DESCRIPTION
                if($type != 'new') {
                    if($merge->envMonitorPlan->time_frequent) {
                        $split_period = explode('-', $merge->envMonitorPlan->time_frequent);
                        if(is_numeric($split_period[0])) {
                            $periodeNumber = $split_period[0];
                        }
                        if(count($split_period) > 1) {
                            $periodeDesc = $split_period[1];
                        }
                    }
                }

                $results[] = [
                    'no' => $total + 1,
                    'id' => $merge->id,
                    'name' => "$changeType $ronaAwal akibat $component",
                    'type' => 'subtitle',
                    'indicator' => $type == 'new' ? null : $merge->envMonitorPlan->indicator,
                    'impact_source' => $type == 'new' ? null : $merge->envMonitorPlan->impact_source,
                    'collection_method' => $type == 'new' ? null : $merge->envMonitorPlan->collection_method,
                    'location' => $type == 'new' ? null : $merge->envMonitorPlan->location,
                    'time_frequent' => $type == 'new' ? null : $merge->envMonitorPlan->time_frequent,
                    'executor' => $type == 'new' ? null : $merge->envMonitorPlan->executor,
                    'supervisor' => $type == 'new' ? null : $merge->envMonitorPlan->supervisor,
                    'report_recipient' => $type == 'new' ? null : $merge->envMonitorPlan->report_recipient,
                    'description' => $type == 'new' ? null : $merge->envMonitorPlan->description,
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

    private function getPoinA($stages, $id_project) {
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
             $results[$idx]['stages'] =  $alphabet_list . '. ' . $s->name;
             $results[$idx]['data'] = [];
 
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

                if(!pA->envMonitorPlan) {
                    continue;
                }

                $results[$idx]['data'][] = [
                    'no' => $total + 1,
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
                $total++;
             }
             $idx++;
         }
 
         return $results;
    }

    private function getPoinB($stages, $id_project) {
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
            $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
            ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->whereNotIn('id', $idPoinA)->whereHas('envImpactAnalysis')->get();
        } else {
            $poinB3 = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
            ->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->whereHas('envImpactAnalysis')->get();
        }

        $data_merge_1 = $poinB1->merge($poinB2);
        $data_merge_final = $data_merge_1->merge($poinB3);

        $alphabet_list = 'A';

        $idx = 0;
        foreach($stages as $s) {
            $results[$idx]['stages'] =  $alphabet_list . '. ' . $s->name;
            $results[$idx]['data'] = [];

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

                if(!$merge->envMonitorPlan) {
                    continue;
                }

                $results[$idx]['data'][] = [
                    'no' => $total + 1,
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
            if($imp->id_sub_project_component != null) {
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
            }
        } catch(ErrorException $err) {
            
        }

        return [
            'component' => $component,
            'ronaAwal' => $ronaAwal
        ];
    }

    private function getComments($id) {
        $komen = Comment::where([['id_impact_identification', $id], ['document_type', 'rpl'],['reply_to', null]])
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