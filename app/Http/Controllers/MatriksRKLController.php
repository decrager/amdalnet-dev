<?php

namespace App\Http\Controllers;

use App\Entity\EnvManagePlan;
use App\Entity\ImpactIdentification;
use App\Entity\Project;
use App\Entity\ProjectStage;
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
                $query->whereHas('envImpactAnalysis');
                $query->whereHas('envManagePlan');
            })->first();

            if($project) {
                return $this->getEnvManagePlan($request->idProject, $stages);
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
        $manage = $request->manage;

        if($request->type == 'new') {
            for($i = 0; $i < count($manage); $i++) {
                $envManage = new EnvManagePlan();
                $envManage->id_impact_identifications = $manage[$i]['id'];
                $envManage->success_indicator = $manage[$i]['success_indicator'];
                $envManage->form = $manage[$i]['form'];
                $envManage->location = $manage[$i]['location'];
                $envManage->period = $manage[$i]['period'];
                $envManage->institution = $manage[$i]['institution'];
                $envManage->description = $manage[$i]['description'];
                $envManage->save();
            }
        } else {
            $ids = [];
            for($i = 0; $i < count($manage); $i++) {
                $envManage = EnvManagePlan::where('id_impact_identifications', $manage[$i]['id'])->first();
                $envManage->success_indicator = $manage[$i]['success_indicator'];
                $envManage->form = $manage[$i]['form'];
                $envManage->location = $manage[$i]['location'];
                $envManage->period = $manage[$i]['period'];
                $envManage->institution = $manage[$i]['institution'];
                $envManage->description = $manage[$i]['description'];
                $envManage->save();

                $ids[] = $manage[$i]['id'];
            }

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
                            'impact_source' => $component,
                            'success_indicator' => null,
                            'form' => null,
                            'location' => null,
                            'period' => null,
                            'institution',
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

    private function getEnvManagePlan($id_project, $stages) {
        $impactIdentifications = ImpactIdentification::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal')
                                      ->where('id_project', $id_project)
                                      ->with(['component.component', 'changeType', 'ronaAwal.rona_awal', 'envManagePlan'])->get();
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
                          'impact_source' => $component,
                          'success_indicator' => $imp->envManagePlan->success_indicator,
                          'form' => $imp->envManagePlan->form,
                          'location' => $imp->envManagePlan->location,
                          'period' => $imp->envManagePlan->period,
                          'institution' => $imp->envManagePlan->institution,
                          'description' => $imp->envManagePlan->description
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
