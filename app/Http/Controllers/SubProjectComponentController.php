<?php

namespace App\Http\Controllers;

use App\Entity\ProjectStage;
use App\Entity\SubProjectComponent;
use App\Entity\Component;
use App\Entity\SubProjectRonaAwal;
use App\Http\Resources\SubProjectComponentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            // cascade delete for sub_project_rona_awals
            SubProjectRonaAwal::select('id')
                ->where('id_sub_project_component', $subProjectComponent->id)
                ->delete();
            $subProjectComponent->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
