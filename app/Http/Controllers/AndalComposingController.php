<?php

namespace App\Http\Controllers;

use App\Entity\EnvImpactAnalysis;
use App\Entity\ImpactIdentification;
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
        $analysis = $request->analysis;

        if($request->type == 'new') {
            for($i = 0; $i < count($analysis); $i++) {
                $envAnalysis = new EnvImpactAnalysis();
                $envAnalysis->id_impact_identifications = $analysis[$i]['id'];
                $envAnalysis->impact_size = $analysis[$i]['impact_size'];
                $envAnalysis->important_trait = $analysis[$i]['important_trait'];
                $envAnalysis->impact_eval_result = $analysis[$i]['impact_eval_result'];
                $envAnalysis->response = $analysis[$i]['response'];
                $envAnalysis->save();
            }
        } else {
            $ids = [];
            for($i = 0; $i < count($analysis); $i++) {
                $envAnalysis = EnvImpactAnalysis::where('id_impact_identifications', $analysis[$i]['id'])->first();
                $envAnalysis->impact_size = $analysis[$i]['impact_size'];
                $envAnalysis->important_trait = $analysis[$i]['important_trait'];
                $envAnalysis->impact_eval_result = $analysis[$i]['impact_eval_result'];
                $envAnalysis->response = $analysis[$i]['response'];
                $envAnalysis->save();

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
                                        ->where('id_project', $id_project)
                                        ->with(['component.component', 'changeType', 'ronaAwal.rona_awal'])->get();
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
                            'important_trait' => null,
                            'impact_eval_result' => null,
                            'response' => null
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
                                        ->where('id_project', $id_project)
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

                        $results[] = [
                            'id' => $imp->id,
                            'name' => "$changeType $ronaAwal akibat $component",
                            'type' => 'subtitle',
                            'component' => $component,
                            'ronaAwal' => $ronaAwal,
                            'impact_size' => $imp->envImpactAnalysis->impact_size,
                            'important_trait' => $imp->envImpactAnalysis->important_trait,
                            'impact_eval_result' => $imp->envImpactAnalysis->impact_eval_result,
                            'response' => $imp->envImpactAnalysis->response
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
