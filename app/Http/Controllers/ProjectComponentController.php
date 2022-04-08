<?php

namespace App\Http\Controllers;

use App\Entity\ProjectComponent;
use App\Entity\Component;
use App\Http\Resources\ProjectComponentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entity\ImpactIdentification;
use App\Entity\SubProjectComponent;
use App\Entity\PotentialImpactEvaluation;
use App\Entity\ProjectRonaAwal;
use App\Entity\SubProjectRonaAwal;
use PhpParser\Node\Expr\Empty_;

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
        $params = $request->all();
        if(isset($params['inquire']) && ($params['inquire'])){
            // $pC = ProjectComponent::where('id', $request->id)->first();
            $res = SubProjectComponent::from('sub_project_components')
               ->select('sub_project_components.*')
               ->join('sub_projects', 'sub_projects.id', '=', 'sub_project_components.id_sub_project')
               ->where('sub_projects.id_project', $request->id_project)
               ->where('sub_project_components.is_andal', $request->mode)
               ->where('sub_project_components.id_component', $request->id)->get();
            return response($res, 200);
        }

        /*return DB::select( DB::raw("
        select c.*, c.name as value, ps.name as project_stage_name,
        pc.id as id_project_component, pc.description, pc.measurement
        from components c
        join project_components pc on pc.id_component = c.id
        left join project_stages ps on ps.id = c.id_project_stage
        where c.deleted_at is null and
        (c.is_master = true or c.originator_id = :val) and pc.is_andal = :andal and pc.id_project = :val
        "), ['val' =>$request->id_project, 'andal' => $request->mode  ]);*/


        // return response($request);
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
          ->where('project_components.is_andal', $request->mode)
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
            $master = Component::where('id', $params['component']['id'])->first();
            if(!$master){
                $master = Component::create([
                    'name' => $params['component']['name'],
                    'id_project_stage' => $params['component']['id_project_stage'],
                    'originator_id' => $params['id_project'],
                    'is_master' => false,
                ]);
                if (!$master){
                    return response( 'Komponen Kegiatan gagal tersimpan', 500);
                }
            }

            $pc = ProjectComponent::firstOrNew([
                'id_project' => $request->id_project,
                'id_component' => $master->id,
                'is_andal' => $request->mode
            ]);
            $pc->description = $params['component']['description'];
            $pc->measurement = $params['component']['measurement'];
            if ($pc->save()) {
                // lookup impact!
                // $imps = ImpactIdentification::where('id_project_component', $pc->id)->all();


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

    // delete
    public function removeProjectComponent(Request $request){
        $params = $request->all();

        if ($request->getChildren) {
            // id_project, id_project_component
            $pC = ProjectComponent::where([
                'id_project' => $request->id_project,
                'id_component' => $request->id_component,
            ])->first();
            return response($pC);
        }
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
        if($projectComponent){
            $mode = $projectComponent->is_andal ? 1 :0;
            $impactClasses = ['App\Entity\ImpactIdentification', 'App\Entity\ImpactIdentificationClone'];
            $pieClasses=['App\Entity\PotentialImpactEvaluation', 'App\Entity\PotentialImpactEvalClone'];
            $pieIdNames= ['id_impact_identification', 'id_impact_identification_clone'];


            $imps = $impactClasses[$mode]::where('id_project_component', $projectComponent->id)->get();
            $nPie = 0;
            $nSRA = 0;
            $nSPC = 0;
            $spcIds = null;
            $nImp = count($imps);
            if($nImp > 0){
                // delete pies
                $ids = [];
                // $hues = [];
                foreach($imps as $imp){
                    $ids[] = $imp->id;
                    // $hues[] = $imp->id_project_rona_awal;
                    $imp->delete();
                }
                $nPie = $pieClasses[$mode]::whereIn($pieIdNames[$mode], $ids)->delete();
            }
            $spcIds = SubProjectComponent::from('sub_project_components')
                ->select('sub_project_components.id')
                ->join('sub_projects', 'sub_projects.id', '=', 'sub_project_components.id_sub_project')
                ->where('sub_project_components.id_component', $projectComponent->id_component)
                ->where('sub_project_components.is_andal', $projectComponent->is_andal)
                ->where('sub_projects.id_project', $projectComponent->id_project)
                ->get();

            $nSRA = SubProjectRonaAwal::whereIn('id_sub_project_component', $spcIds)->delete();
            $nSPC = SubProjectComponent::whereIn('id', $spcIds)->delete();

            $co = Component::where('id', $projectComponent->id_component)->first();
            $pc_ka = ProjectComponent::where([
                'id_project' => $projectComponent->id_project,
                'is_andal' => $projectComponent->is_andal ? false : true,
                'id_component' => $projectComponent->id_component
            ])->pluck('id');
            if(($co) && (!$co->is_master) && ($co->originator_id === $projectComponent->id_project) && (count($pc_ka) === 0)){
                $co->delete();
            }
            return response([$projectComponent->delete(), $spcIds], 200);
        }
        return response('Komponen Kegiatan tidak ditemukan', 200);
    }
}
