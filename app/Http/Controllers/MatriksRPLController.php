<?php

namespace App\Http\Controllers;

use App\Entity\EnvImpactAnalysis;
use App\Entity\EnvManagePlan;
use App\Entity\EnvMonitorPlan;
use App\Entity\ImpactIdentification;
use App\Entity\Institution;
use App\Entity\Project;
use App\Entity\ProjectStage;
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
            
            $project = Project::where('id', $request->idProject)->whereHas('impactIdentifications', function($query) {
                $query->whereHas('envImpactAnalysis');
                $query->whereHas('envMonitorPlan');
            })->first();

            if($project) {
                return $this->getEnvMonitorPlan($request->idProject, $stages);
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
        $monitor = $request->monitor;

        if($request->type == 'new') {
            for($i = 0; $i < count($monitor); $i++) {
                $envMonitor = new EnvMonitorPlan();
                $envMonitor->id_impact_identifications = $monitor[$i]['id'];
                $envMonitor->collection_method = $monitor[$i]['collection_method'];
                $envMonitor->location = $monitor[$i]['location'];
                $envMonitor->time_frequent = $monitor[$i]['time_frequent'];
                $envMonitor->executor_institution_id = $monitor[$i]['executor'];
                $envMonitor->supervisor_institution_id = $monitor[$i]['supervisor'];
                $envMonitor->recipient_institution_id = $monitor[$i]['report_recipient'];
                $envMonitor->description = $monitor[$i]['description'];
                $envMonitor->save();
            }
        } else {
            $ids = [];
            for($i = 0; $i < count($monitor); $i++) {
                $envMonitor = EnvMonitorPlan::where('id_impact_identifications', $monitor[$i]['id'])->first();
                $envMonitor->collection_method = $monitor[$i]['collection_method'];
                $envMonitor->location = $monitor[$i]['location'];
                $envMonitor->time_frequent = $monitor[$i]['time_frequent'];
                $envMonitor->executor_institution_id = $monitor[$i]['executor'];
                $envMonitor->supervisor_institution_id = $monitor[$i]['supervisor'];
                $envMonitor->recipient_institution_id = $monitor[$i]['report_recipient'];
                $envMonitor->description = $monitor[$i]['description'];
                $envMonitor->save();

                $ids[] = $monitor[$i]['id'];
            }

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
                        // Check env manage plan
                        $indicator = null;
                        $manage = EnvManagePlan::where('id_impact_identifications', $imp->id)->first();
                        if($manage) {
                            $indicator = $manage->success_indicator;
                        }
                    

                        $changeType = $imp->id_change_type ? $imp->changeType->name : '';
                        $ronaAwal =  $imp->ronaAwal->id_rona_awal ? $imp->ronaAwal->rona_awal->name : $imp->ronaAwal->name;
                        $component = $imp->component->id_component ? $imp->component->component->name : $imp->component->name;

                        $results[] = [
                            'id' => $imp->id,
                            'name' => "$changeType $ronaAwal akibat $component",
                            'type' => 'subtitle',
                            'indicator' => $indicator,
                            'impact_source' => $component,
                            'collection_method' => null,
                            'location' => null,
                            'time_frequent' => null,
                            'executor' => null,
                            'supervisor' => null,
                            'report_recipient' => null,
                            'description' => null
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

    private function getEnvMonitorPlan($id_project, $stages) {
        $impactIdentifications = ImpactIdentification::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
                                      ->where('id_project', $id_project)
                                      ->with(['component.component', 'changeType', 'ronaAwal.rona_awal', 'envMonitorPlan'])->get();
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
                       // Check env manage plan
                       $indicator = null;
                       $manage = EnvManagePlan::where('id_impact_identifications', $imp->id)->first();
                       if($manage) {
                           $indicator = $manage->success_indicator;
                       }

                      $changeType = $imp->id_change_type ? $imp->changeType->name : '';
                      $ronaAwal =  $imp->ronaAwal->id_rona_awal ? $imp->ronaAwal->rona_awal->name : $imp->ronaAwal->name;
                      $component = $imp->component->id_component ? $imp->component->component->name : $imp->component->name;

                      $results[] = [
                          'id' => $imp->id,
                          'name' => "$changeType $ronaAwal akibat $component",
                          'type' => 'subtitle',
                          'indicator' => $indicator,
                          'impact_source' => $component,
                          'collection_method' =>  $imp->envMonitorPlan->collection_method,
                          'location' => $imp->envMonitorPlan->location,
                          'time_frequent' => $imp->envMonitorPlan->time_frequent,
                          'executor' => $imp->envMonitorPlan->executor_institution_id,
                          'supervisor' => $imp->envMonitorPlan->supervisor_institution_id,
                          'report_recipient' => $imp->envMonitorPlan->recipient_institution_id,
                          'description' => $imp->envMonitorPlan->description
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
