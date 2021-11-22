<?php

namespace App\Http\Controllers;

use App\Entity\AndalComment;
use App\Entity\EnvImpactAnalysis;
use App\Entity\ImpactAnalysisDetail;
use App\Entity\ImpactIdentification;
use App\Entity\ImportantTrait;
use App\Entity\Project;
use App\Entity\ProjectStage;
use Illuminate\Http\Request;

class AndalComposingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->comment) {
            $comments = [];
            if($request->role == 'true') {
                $comments = AndalComment::where([['id_project', $request->idProject], ['id_user', $request->idUser]])->orderBy('id', 'DESC')->with(['user' => function($q) {
                    $q->select(['id', 'name']);
                }])->get();
            } else {
                $comments = AndalComment::where('id_project', $request->idProject)->orderBy('id', 'DESC')->with(['user' => function($q) {
                    $q->select(['id', 'name']);
                }])->get();
            }

            return $comments;
        }

        if($request->project) {
            return Project::whereHas('impactIdentifications')->get();
        }

        if($request->lastTime && $request->idProject) {
            $id_project = $request->idProject;
            $time = EnvImpactAnalysis::whereHas('impactIdentification', function($q) use($id_project) {
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
                $query->whereHas('envImpactAnalysis');
            })->first();

            if($project) {
                return $this->getEnvImpactAnalysis($request->idProject, $stages);
            } else {
                return $this->getImpactNotifications($request->idProject, $stages);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->type == 'comment') {
            $comment = new AndalComment();
            $comment->id_project = $request->idProject;
            $comment->id_user = $request->idUser;
            $comment->comment = $request->comment;
            $comment->save();

            return AndalComment::whereKey($comment->id)->with('user')->first();
        }

        $analysis = $request->analysis;

        if($request->type == 'new') {
            for($i = 0; $i < count($analysis); $i++) {
                $envAnalysis = new EnvImpactAnalysis();
                $envAnalysis->id_impact_identifications = $analysis[$i]['id'];
                $envAnalysis->impact_size = $analysis[$i]['impact_size'];
                $envAnalysis->impact_eval_result = $analysis[$i]['impact_eval_result'];
                $envAnalysis->impact_type = $analysis[$i]['impact_type'];
                $envAnalysis->studies_condition = $analysis[$i]['studies_condition'];
                $envAnalysis->condition_dev_no_plan = $analysis[$i]['condition_dev_no_plan'];
                $envAnalysis->condition_dev_with_plan = $analysis[$i]['condition_dev_with_plan'];
                $envAnalysis->impact_size_difference = $analysis[$i]['impact_size_difference'];
                $envAnalysis->save();

                $important_trait = $analysis[$i]['important_trait'];

                for($a = 0; $a < count($important_trait); $a++) {
                    $detail = new ImpactAnalysisDetail();
                    $detail->id_env_impact_analysis = $envAnalysis->id;
                    $detail->id_important_trait = $important_trait[$a]['id'];
                    $detail->important_trait = $important_trait[$a]['important_trait'];
                    $detail->description = $important_trait[$a]['desc'];
                    $detail->save();
                }
            }
        } else {
            $ids = [];
            for($i = 0; $i < count($analysis); $i++) {
                $envAnalysis = EnvImpactAnalysis::where('id_impact_identifications', $analysis[$i]['id'])->first();
                $envAnalysis->impact_size = $analysis[$i]['impact_size'];
                $envAnalysis->impact_eval_result = $analysis[$i]['impact_eval_result'];
                $envAnalysis->impact_type = $analysis[$i]['impact_type'];
                $envAnalysis->studies_condition = $analysis[$i]['studies_condition'];
                $envAnalysis->condition_dev_no_plan = $analysis[$i]['condition_dev_no_plan'];
                $envAnalysis->condition_dev_with_plan = $analysis[$i]['condition_dev_with_plan'];
                $envAnalysis->impact_size_difference = $analysis[$i]['impact_size_difference'];
                $envAnalysis->save();

                $important_trait = $analysis[$i]['important_trait'];

                for($a = 0; $a < count($important_trait); $a++) {
                    $detail = ImpactAnalysisDetail::where([['id_env_impact_analysis', $envAnalysis->id], ['id_important_trait', $important_trait[$a]['id']]])->first();
                    $detail->important_trait = $important_trait[$a]['important_trait'];
                    $detail->description = $important_trait[$a]['desc'];
                    $detail->save();
                }

                $ids[] = $analysis[$i]['id'];
            }

            $lastTime = EnvImpactAnalysis::whereIn('id_impact_identifications', $ids)->orderBy('updated_at', 'DESC')->first()
                        ->updated_at->locale('id')->diffForHumans();

            return 'Diperbarui ' . $lastTime;
        }

        return 'Diperbarui ' . EnvImpactAnalysis::orderBy('id', 'DESC')->first()->updated_at->locale('id')->diffForHumans();
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

    private function getImpactNotifications($id_project, $stages) {
        $impactIdentifications = ImpactIdentification::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
                                        ->where([['id_project', $id_project],['is_hypothetical_significant', true]])
                                        ->with(['component.component', 'changeType', 'ronaAwal.rona_awal'])->get();
        
        $important_trait = ImportantTrait::select('id', 'description')->get();
        $traits = [];

        foreach($important_trait as $it) {
            $traits[] = [
                'id' => $it->id,
                'description' => $it->description,
                'desc' => null,
                'important_trait' => null
            ];
        }
        
        
        $results = [];

        foreach($stages as $s) {
            $results[] = [
                'id' => $s->id,
                'name' => $s->name,
                'type' => 'title'
            ];

            $total = 0;

            foreach($impactIdentifications as $imp) {
                if($imp->component->id_project_stage == $s->id || $imp->component->component->id_project_stage == $s->id) {
                    $changeType = $imp->id_change_type ? $imp->changeType->name : '';
                    $ronaAwal =  $imp->ronaAwal->id_rona_awal ? $imp->ronaAwal->rona_awal->name : $imp->ronaAwal->name;
                    $component = $imp->component->id_component ? $imp->component->component->name : $imp->component->name;

                    $results[] = [
                        'id' => $imp->id,
                        'name' => "$changeType $ronaAwal akibat $component",
                        'type' => 'subtitle',
                        'component' => $component,
                        'ronaAwal' => $ronaAwal,
                        'impact_size' => null,
                        'important_trait' => $traits,
                        'impact_type' => null,
                        'impact_eval_result' => null,
                        'studies_condition' => null,
                        'condition_dev_no_plan' => null,
                        'condition_dev_with_plan' => null,
                        'impact_size_difference' => null
                    ];
                    $total++;
                }
            }

            if($total == 0) {
                array_pop($results);
            }
        }
           
        return $results;
    }

    private function getEnvImpactAnalysis($id_project, $stages) {
          $impactIdentifications = ImpactIdentification::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
                                        ->where([['id_project', $id_project],['is_hypothetical_significant', true]])
                                        ->with(['component.component', 'changeType', 'ronaAwal.rona_awal', 'envImpactAnalysis'])->get();
        $results = [];

        foreach($stages as $s) {
            $results[] = [
                'id' => $s->id,
                'name' => $s->name,
                'type' => 'title'
            ];

            $total = 0;

            foreach($impactIdentifications as $imp) {
                if($imp->component->id_project_stage == $s->id || $imp->component->component->id_project_stage == $s->id) {
                    $changeType = $imp->id_change_type ? $imp->changeType->name : '';
                    $ronaAwal =  $imp->ronaAwal->id_rona_awal ? $imp->ronaAwal->rona_awal->name : $imp->ronaAwal->name;
                    $component = $imp->component->id_component ? $imp->component->component->name : $imp->component->name;

                    $important_trait = [];

                    foreach($imp->envImpactAnalysis->detail as $det) {
                        $important_trait[] = [
                            'id' => $det->id_important_trait,
                            'description' => $det->importantTrait->description,
                            'desc' => $det->description,
                            'important_trait' => $det->important_trait
                        ];
                    }

                    $results[] = [
                        'id' => $imp->id,
                        'name' => "$changeType $ronaAwal akibat $component",
                        'type' => 'subtitle',
                        'component' => $component,
                        'ronaAwal' => $ronaAwal,
                        'impact_size' => $imp->envImpactAnalysis->impact_size,
                        'important_trait' => $important_trait,
                        'impact_type' => $imp->envImpactAnalysis->impact_type,
                        'impact_eval_result' => $imp->envImpactAnalysis->impact_eval_result,
                        'studies_condition' => $imp->envImpactAnalysis->studies_condition,
                        'condition_dev_no_plan' => $imp->envImpactAnalysis->condition_dev_no_plan,
                        'condition_dev_with_plan' => $imp->envImpactAnalysis->condition_dev_with_plan,
                        'impact_size_difference' => $imp->envImpactAnalysis->impact_size_difference
                    ];
                    $total++;
                }
            }

            if($total == 0) {
                array_pop($results);
            }
        }
           
        return $results;
    }
}
