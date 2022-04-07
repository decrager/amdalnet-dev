<?php

namespace App\Http\Controllers;

use App\Entity\ProjectStage;
use App\Entity\SubProjectComponent;
use App\Entity\Component;
use App\Entity\SubProjectRonaAwal;
use App\Http\Resources\SubProjectComponentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entity\ImpactIdentification;
use App\Entity\ProjectComponent;
use App\Entity\SubProject;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use App\Entity\ProjectRonaAwal;
use App\Entity\PotentialImpactEvaluation;

class SubProjectComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if(isset($params['inquire']) && ($params['inquire'])){
           $spra = SubProjectRonaAwal::where('id_sub_project_component', $request->id_sub_project_component)
                ->get();
            return response($spra, 200);
        }
        if (isset($params['id_project'])){
            $components = SubProjectComponent::select('sub_project_components.*',
                'components.name AS name_master',
                'components.id_project_stage AS id_project_stage_master',
                'sub_projects.type')
                ->leftJoin('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
                ->leftJoin('projects', 'sub_projects.id_project', '=', 'projects.id')
                ->leftJoin('components', 'sub_project_components.id_component', '=', 'components.id')
                ->where('sub_projects.id_project', $params['id_project'])
                ->get();
            if (isset($params['group']) && $params['group']) {
                $ids = [4,1,2,3];
                $stages = ProjectStage::select('project_stages.*')->get()->sortBy(function($model) use($ids) {
                    return array_search($model->getKey(),$ids);
                });
                $data = [];
                foreach ($stages as $stage) {
                    $listUtama = [];
                    $listPendukung = [];
                    foreach ($components as $component) {
                        if ($component->id_project_stage === $stage->id
                            || $component->id_project_stage_master === $stage->id) {
                            if ($component->type == 'utama') {
                                array_push($listUtama, $component);
                            } else if ($component->type == 'pendukung') {
                                array_push($listUtama, $component);
                            }
                        }
                    }
                    array_push($data, [
                        'project_stage' => $stage,
                        'utama' => $listUtama,
                        'pendukung' => $listPendukung,
                    ]);
                }
                return [
                    'data' => $data,
                ];
            } else {
                return SubProjectComponentResource::collection($components);
            }
        } else if (isset($params['id_sub_project'])){
            $id_sub_project = $params['id_sub_project'];

            if (isset($params['id_project_stage'])) {
                $id_project_stage = $params['id_project_stage'];
                $level1 = SubProjectComponent::with('component')
                    ->where('id_sub_project', $id_sub_project)
                    ->where('id_project_stage', $id_project_stage)
                    ->get();
                $nested = SubProjectComponent::with('component')->whereHas('component', function($q) use ($id_project_stage) {
                        $q->where('id_project_stage', $id_project_stage);
                    })->where('id_sub_project', $id_sub_project)->get();
                $components = $level1->merge($nested);
                return SubProjectComponentResource::collection($components);
            } else {
                $components = SubProjectComponent::select('sub_project_components.*')
                    ->where('id_sub_project', $id_sub_project)
                    ->orderBy('id', 'asc')
                    ->get();
                return SubProjectComponentResource::collection($components);
            }
        } else {
            return SubProjectComponentResource::collection(SubProjectComponent::with('component')->get());
        }
    }

    public function subProjectComponents(Request $request){

        /* $params = $request->all();
        if (!isset($params['id_project']) || !$params['id_project'])
        {
            return response('no project id is specified', 500);
        }

        return SubProjectComponent::from('sub_project_components')
          ->select(
              'sub_project_components.id',
              ''
          )*/


        // commented by HH, on 20220326
        $params = $request->all();
        if (!isset($params['id_project']) || !$params['id_project'])
        {
            return response('no project specified', 500);
        }

        return response(Component::from('components')->
              select(
                  'components.*',
                  'project_stages.name as project_stage_name',
                  'sub_project_components.id as id_sub_project_component',
                  'sub_projects.id as id_sub_project',
                  'sub_project_components.description_specific as description',
                  'sub_project_components.unit as measurement'
              )
              ->join('sub_project_components', 'sub_project_components.id_component','=', 'components.id')
              ->join('sub_projects', 'sub_projects.id', '=', 'sub_project_components.id_sub_project')
              ->join('projects', 'projects.id', '=', 'sub_projects.id_project')
              ->leftJoin('project_stages', 'project_stages.id', '=', 'components.id_project_stage')
              ->where('projects.id', $request->id_project)
              ->where('sub_project_components.is_andal', $request->mode)
              ->get());

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
        // id_sub_project, id_component, desc, unit
        $params = $request->all();
        if(isset($params['id_sub_project']) && $params['id_sub_project']) {
            $spc = SubProjectComponent::firstOrNew([
                'id_sub_project' => $request->id_sub_project,
                'id_component' => $request->id_component,
                'is_andal' => $request->mode
            ]);

            $spc->description_specific = $request->description;
            $spc->unit = $request->measurement;
            if ($spc->save()){
                return response($spc->id, 200);
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\SubProjectComponent  $subProjectComponent
     * @return \Illuminate\Http\Response
     */
    public function show(SubProjectComponent $subProjectComponent)
    {
        return $subProjectComponent;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\SubProjectComponent  $subProjectComponent
     * @return \Illuminate\Http\Response
     */
    public function edit(SubProjectComponent $subProjectComponent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\SubProjectComponent  $subProjectComponent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubProjectComponent $subProjectComponent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\SubProjectComponent  $subProjectComponent
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubProjectComponent $subProjectComponent)
    {

        try {
            // get sister subprojects

            $spra = SubProjectRonaAwal::where('id_sub_project_component', $subProjectComponent->id)->get();

            if($spra){

                $sp = SubProject::where('id', $subProjectComponent->id_sub_project)->first();
                $sSP = SubProject::from('sub_projects')
                    ->select('sub_projects.id')
                    ->where('sub_projects.id_project', $sp->id_project)
                    ->where('sub_projects.id', '<>', $sp->id)->get();
                $idSubProjects = [];
                foreach($sSP as $s){
                    $idSubProjects[] = $s->id;
                }


                foreach($spra as $sra){

                    // if if there's other combination of
                    $id_rona_awal = $sra->id_rona_awal;
                    $spcra = SubProjectComponent::from('sub_project_components')
                      ->select('sub_project_components.id as id_spc', 'sub_project_rona_awals.id as id_spra')
                      ->join('sub_project_rona_awals', 'sub_project_rona_awals.id_sub_project_component', '=', 'sub_project_components.id' )
                      ->where('sub_project_rona_awals.id_rona_awal', $id_rona_awal)
                      ->where('sub_project_rona_awals.is_andal', $subProjectComponent->is_andal)
                      ->where('sub_project_components.is_andal', $subProjectComponent->is_andal)
                      ->where('sub_project_components.id_component', $subProjectComponent->id_component)
                      ->whereIn('sub_project_components.id_sub_project', $idSubProjects)->get();

                    if(count($spcra) === 0){
                        // delete impacts with id_component and id_rona_awal
                        $impactClasses = ['App\Entity\ImpactIdentification', 'App\Entity\ImpactIdentificationClone'];
                        $pieClasses=['App\Entity\PotentialImpactEvaluation', 'App\Entity\PotentialImpactEvalClone'];
                        $pieIdNames= ['id_impact_identification', 'id_impact_identification_clone'];

                        $mode = $subProjectComponent->is_andal? 1 : 0;
                        $pc = ProjectComponent::where(['id_project' => $sp->id_project, 'id_component' => $subProjectComponent->id_component])->first();
                        $pra = ProjectRonaAwal::where(['id_project' => $sp->id_project, 'id_rona_awal' => $id_rona_awal])->first();
                        $imp = $impactClasses[$mode]::where([
                            'id_project' => $sp->id_project,
                            'id_project_component' => $pc->id,
                            'id_project_rona_awal' => $pra->id
                        ])->first();
                        if($imp){
                            $pie = $pieClasses[$mode]::where($pieIdNames[$mode], $imp->id)->delete();
                            $imp->delete();
                        }
                    }

                    $sra->delete();
                }
            }
            return response($subProjectComponent->delete(), 200);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
