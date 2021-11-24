<?php

namespace App\Http\Controllers;

use App\Entity\Comment;
use App\Entity\EnvManagePlan;
use App\Entity\ImpactIdentification;
use App\Entity\Project;
use App\Entity\ProjectStage;
use App\Entity\RklRplComment;
use Illuminate\Http\Request;

class MatriksRKLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
            
            $project = Project::where('id', $request->idProject)->whereHas('impactIdentifications', function($query) {
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
        if($request->type == 'impact-comment') {
            $comment = new Comment();
            $comment->id_user = $request->id_user;
            $comment->id_impact_identification = $request->id_impact_identification;
            $comment->description = $request->description;
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
                    'replies' => [
                        'id' => null,
                        'created_at' => null,
                        'description' => null
                ]
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
        $poinA = ImpactIdentification::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where('id_project', $id_project)->whereHas('envImpactAnalysis', function($q) {
            $q->whereHas('detail', function($query) {
                $query->where('important_trait', '+P');
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
            if($pA->component->id_project_stage == $s->id || $pA->component->component->id_project_stage == $s->id) {
                $changeType = $pA->id_change_type ? $pA->changeType->name : '';
                // $ronaAwal =  $pA->ronaAwal->id_rona_awal ? $pA->ronaAwal->rona_awal->name : $pA->ronaAwal->name;
                // $component = $pA->component->id_component ? $pA->component->component->name : $pA->component->name;
                $ronaAwal =  $pA->subProjectRonaAwal->id_rona_awal ? $pA->subProjectRonaAwal->ronaAwal->name : $pA->subProjectRonaAwal->name;
                $component = $pA->subProjectComponent->id_component ? $pA->subProjectComponent->component->name : $pA->subProjectComponent->name;

                $komen = Comment::where([['id_impact_identification', $pA->id], ['document_type', 'rkl'],['reply_to', null]])
                            ->orderBY('id', 'DESC')->get();
                
                $comments = [];
                foreach($komen as $c) {
                    $comments[] = [
                        'id' => $c->id,
                        'id_impact_identification' => $c->id_impact_identification,
                        'created_at' => $c->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                        'user' => $c->user->name,
                        'is_checked' => $c->is_checked,
                        'description' => $c->description,
                        'replies' => [
                            'id' => $c->reply ? $c->reply->id : null,
                            'created_at' => $c->reply ? $c->reply->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss') : null,
                            'description' => $c->reply ? $c->reply->description : null
                        ]
                    ];
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
    $poinB1 = ImpactIdentification::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where([['id_project', $id_project],['is_managed', true]])->get();

    //  =========== POIN B.2 ============= //
    // DAMPAK TIDAK PENTING HIPOTETIK YANG DIKELOLA (DTPHK)
    $poinB2 = ImpactIdentification::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
        ->where([['id_project', $id_project],['is_managed', false],['initial_study_plan', '!=', null]])->get();

    // ============ POIN B.3 ============= //
    // DAMPAK TIDAK PENTING (HASIL MATRIK ANDAL (TP))
    $poinB3 = ImpactIdentification::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')
    ->where('id_project', $id_project)->whereHas('envImpactAnalysis', function($q) {
        $q->whereDoesntHave('detail', function($query) {
            $query->where('important_trait', '+P');
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
            if($merge->component->id_project_stage == $s->id || $merge->component->component->id_project_stage == $s->id) {
                $changeType = $merge->id_change_type ? $merge->changeType->name : '';
                // $ronaAwal =  $merge->ronaAwal->id_rona_awal ? $merge->ronaAwal->rona_awal->name : $merge->ronaAwal->name;
                // $component = $merge->component->id_component ? $merge->component->component->name : $merge->component->name;
                $ronaAwal =  $merge->subProjectRonaAwal->id_rona_awal ? $merge->subProjectRonaAwal->ronaAwal->name : $merge->subProjectRonaAwal->name;
                $component = $merge->subProjectComponent->id_component ? $merge->subProjectComponent->component->name : $merge->subProjectComponent->name;

                $komen = Comment::where([['id_impact_identification', $merge->id], ['document_type', 'rkl'],['reply_to', null]])
                            ->orderBY('id', 'DESC')->get();
                
                $comments = [];
                foreach($komen as $c) {
                    $comments[] = [
                        'id' => $c->id,
                        'id_impact_identification' => $c->id_impact_identification,
                        'created_at' => $c->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss'),
                        'user' => $c->user->name,
                        'is_checked' => $c->is_checked,
                        'description' => $c->description,
                        'replies' => [
                            'id' => $c->reply ? $c->reply->id : null,
                            'created_at' => $c->reply ? $c->reply->updated_at->locale('id')->isoFormat('D MMMM Y hh:mm:ss') : null,
                            'description' => $c->reply ? $c->reply->description : null
                        ]
                    ];
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
        }

        if($total == 0) {
            array_pop($results);
        } else {
            $alphabet_list++;
        }
    }

    return $results;
  }
}
