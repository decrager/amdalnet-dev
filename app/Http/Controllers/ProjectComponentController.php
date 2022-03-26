<?php

namespace App\Http\Controllers;

use App\Entity\ProjectComponent;
use App\Entity\Component;
use App\Http\Resources\ProjectComponentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*
        $params = $request->all();
        if (isset($params['id_project'])){
            $components = ProjectComponent::select('project_components.*',
                'components.name AS name_master',
                'components.id_project_stage AS id_project_stage_master')
                ->leftJoin('components', 'project_components.id_component', '=', 'components.id')
                ->where('project_components.id_project', $params['id_project'])
                ->get();
            return ProjectComponentResource::collection($components);
        } else {
            return ProjectComponentResource::collection(ProjectComponent::with('component')->get());
        }*/

        /**
         * 20220324
         * id, id_project_stage, name, is_master, description, measurement, id_project_component
         */

        /* $params = $request->all();) */
        return response(Component::from('components')
          ->selectRaw('
            components.*,
            components.name as value,
            project_stages.name as project_stage_name,
            project_components.id as id_project_component,
            project_components.description as description,
            project_components.measurement as measurement
          ')
          ->join('project_components', 'project_components.id_component', '=', 'components.id' )
          ->leftJoin('project_stages', 'project_stages.id', 'components.id_project_stage')
          ->where(function ($q) use ($request) {
              $q->where('components.is_master', true);
              $q->orWhere('components.originator_id', $request->id_project);
              return $q;
          })
          ->where('project_components.id_project', $request->id_project)->get());

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

        $params = $request->all();
        if(isset($params['id_project']) && isset($params['component'])){
            $master = Component::first('id', $params['component']['id']);
            if(!$master){
                $master = Component::create([
                    'name' => $params['component']['name'],
                    'id_project_stage' => $params['component']['id_project_stage'],
                    'originator_id' => $params['id_project'],
                    'is_master' => false,
                ]);
            }

            $pc = ProjectComponent::firstOrNew([
                'id_project' => $request->id_project,
                'id_component' => $master->id,
            ]);
            $pc->description = $params['component']['description'];
            $pc->measurement = $params['component']['measurement'];
            if ($pc->save()) {
                return response()->json([
                    'code' => 200,
                    'data' =>  $pc

                ]);
            }
            return response()->json(['code' => 500]);
        }

        return response()->json(['code' => 500]);

        /*
        $all_params = $request->all();
        if (isset($all_params['components'])){
            $validator = $request->validate([
                'components' => 'required',
            ]);
            DB::beginTransaction();
            // clear items
            $first = $validator['components'][0];
            ProjectComponent::where('id_project', $first['id_project'])->delete();
            $num_created = 0;
            foreach ($validator['components'] as $component){
                // create new
                $component['id'] == null;
                if ($component['id_component'] > 99999999) {
                    $component['id_component'] = null;
                } else {
                    // only save id_component
                    $component['id_project_stage'] = null;
                    $component['name'] = null;
                }
                if (ProjectComponent::create($component)){
                    $num_created++;
                }
            }
            if ($num_created == count($validator['components'])){
                DB::commit();
                return response()->json(['code' => 200]);
            } else {
                DB::rollBack();
                return response()->json(['code' => 500]);
            }
        } else {
            $validator = $request->validate([
                'id_project' => 'required',
                'id_component' => 'required',
                'id_project_stage' => 'required',
                'name' => 'required',
            ]);
            $validator['id'] == null;
            if ($validator['id_component'] != null){
                // only save id_component
                $validator['id_project_stage'] = null;
                $validator['name'] = null;
            }
            DB::beginTransaction();
            if (ProjectComponent::create($validator)){
                DB::commit();
                return response()->json(['code' => 200]);
            } else {
                DB::rollBack();
                return response()->json(['code' => 500]);
            }
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ProjectComponent  $projectComponent
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectComponent $projectComponent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectComponent  $projectComponent
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectComponent $projectComponent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectComponent  $projectComponent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectComponent $projectComponent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectComponent  $projectComponent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectComponent $projectComponent)
    {
        //
    }
}
