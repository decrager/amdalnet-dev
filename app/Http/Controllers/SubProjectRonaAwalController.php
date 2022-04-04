<?php

namespace App\Http\Controllers;

use App\Entity\ComponentType;
use App\Entity\SubProjectRonaAwal;
use App\Entity\RonaAwal;
use App\Entity\ImpactIdentification;
use App\Http\Resources\SubProjectRonaAwalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entity\PotentialImpactEvalClone;
use App\Entity\PotentialImpactEvaluation;
use App\Entity\ProjectRonaAwal;
use App\Entity\ProjectComponent;
use App\Entity\SubProjectComponent;
use App\Entity\Component;
use App\Entity\SubProject;

class SubProjectRonaAwalController extends Controller
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
            /* $spra = SubProjectRonaAwal::where('id', $request->id_sub_project_rona_awal)->first();
            $spc = SubProjectComponent::where('id', $request->id_sub_project_component)->first();
            if($spra && $spc){
                $pc = ProjectComponent::where(['id_project' => $request->id_project, 'id_component' => $spc->id_component])
            }
            return response([], 200);*/
            return response([1], 200);
        }

        if(isset($params['id_project'] ) && (isset($params['scoping']) && ($params['scoping']))) {
            return DB::select( DB::raw("
            select rona_awal.*,
            rona_awal.name as value,
            sub_project_rona_awals.description_specific as description,
            sub_project_rona_awals.unit as measurement,
            sub_project_rona_awals.id as id_sub_project_rona_awal,
            sub_project_components.id as id_sub_project_component,
            components.id_project_stage as id_project_stage,
            sub_projects.id as id_sub_project
          from rona_awal
          join sub_project_rona_awals on sub_project_rona_awals.id_rona_awal = rona_awal.id
          join sub_project_components on sub_project_components.id = sub_project_rona_awals.id_sub_project_component
          join sub_projects on sub_projects.id = sub_project_components.id_sub_project
          left join components on components.id = sub_project_components.id_component
          join projects on projects.id = sub_projects.id_project
          where projects.id = :val"), ['val' =>$request->id_project]);


            /*return response(RonaAwal::from('rona_awal')->select(
                'rona_awal.*',
                'rona_awal.name as value',
                'sub_project_rona_awals.description_specific as description',
                'sub_project_rona_awals.unit as measurement',
                'sub_project_rona_awals.id as id_sub_project_rona_awal',
                'sub_project_components."id" as id_sub_project_component'
            )
            ->join('sub_project_rona_awals', 'sub_project_rona_awals.id_rona_awal', '=', 'rona_awal.id' )
            ->join('sub_project_components', 'sub_project_components.id','','sub_project_rona_awals.id_sub_project_component')
            ->join('sub_projects', 'sub_projects.id', '=', 'sub_project_components.id_sub_project')
            ->join('projects', 'projects.id', '=', 'sub_projects.id_project')
            ->where('projects.id',$request->id_project)
            ->get(), 200);*/
        }

        if (isset($params['id_project'])){
            $rona_awals = SubProjectRonaAwal::select('sub_project_rona_awals.*',
                'rona_awal.name AS name_master',
                'rona_awal.id_component_type AS id_component_type_master',
                'p.id AS id_project')
                ->leftJoin('rona_awal', 'sub_project_rona_awals.id_rona_awal', '=', 'rona_awal.id')
                ->leftJoin('sub_project_components AS spc', 'sub_project_rona_awals.id_sub_project_component', '=', 'spc.id')
                ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
                ->leftJoin('projects AS p', 'sp.id_project', '=', 'p.id')
                ->where('p.id', $params['id_project'])
                ->orderBy('name_master', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();
            if (isset($params['with_component_type']) && $params['with_component_type']){
                $component_types = ComponentType::select('component_types.*')
                    ->orderBy('id', 'asc')
                    ->get();
                $data = [];
                foreach ($rona_awals as $rona_awal) {
                    $id_component_type = $rona_awal['id_component_type_master'];
                    if ($id_component_type == null) {
                        $id_component_type = $rona_awal['id_component_type'];
                    }
                    $data[$id_component_type][] = $rona_awal;
                }
                $data_output = [];
                $dummyId = 99999999;
                foreach ($component_types as $component_type) {
                    $id = $component_type['id'];
                    $count_data = 0;
                    if (array_key_exists($id, $data)) {
                        $count_data = count($data[$id]);
                    }
                    array_push($data_output, [
                        'id' => $dummyId,
                        'is_component_type' => true,
                        'component_type_name' => $component_type['name'],
                    ]);
                    if (array_key_exists($id, $data)) {
                        foreach ($data[$id] as $item) {
                            $item['is_component_type'] = false;
                            array_push($data_output, $item);
                        }
                    }
                    $dummyId++;
                }
                return [
                    'data' => $data_output,
                ];
            } else {
                return SubProjectRonaAwalResource::collection($rona_awals);
            }
        } else {
            return SubProjectRonaAwalResource::collection(SubProjectRonaAwal::with('rona_awal')->get());
        }
    }

    public function subProjectHues(Request $request)
    {
        $params = $request->all();

        $res = RonaAwal::from("rona_awal")
        ->select(
            'rona_awal.*',
            'rona_awal.name as value',
            'sub_project_rona_awals.description_specific as description',
            'sub_project_rona_awals.unit as measurement',
            'sub_project_rona_awals.id as id_sub_project_rona_awal',


        )
        ->join('sub_project_rona_awals', 'sub_project_rona_awals.id_rona_awal', '=', 'rona_awal.id' )
        ->join('sub_project_components', 'sub_project_components.id','','sub_project_rona_awals.id_sub_project_component')
        ->join('sub_projects', 'sub_projects.id', '=', 'sub_project_components.id_sub_project')
        ->join('projects', 'projects.id', '=', 'sub_projects.id_project')
        ->where('projects.id',$request->id_project);

        return response($res->get(), 200);

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
        // id, name, description, measurement, id_sub_project_component
        $params = $request->all();
        if(isset($params['id_sub_project_component']) && $params['id_sub_project_component']) {
            $spr = SubProjectRonaAwal::firstOrNew([
                'id_sub_project_component' => $request->id_sub_project_component,
                'id_rona_awal' => $request->id,
            ]);

            $spr->description_specific = $request->description;
            $spr->unit = $request->measurement;
            if ($spr->save()){
                // save impact
                $imp = ImpactIdentification::firstOrCreate([
                    'id_project' => $request->id_project,
                    'id_project_component' => $request->id_project_component,
                    'id_project_rona_awal' => $request->id_project_rona_awal,
                ]);

                // save pies
                if ($imp) {

                    $pc = ProjectComponent::where('id', $imp->id_project_component)->first();
                    $component = Component::where('id', $pc->id_component)->first();

                    $spcs = SubProjectComponent::from('sub_project_components')
                        ->select('sub_project_components.id',
                          'sub_project_components.description_specific as description',
                          'sub_project_components.unit as measurement',
                          'sub_projects.name as sub_project_name'
                        )
                        ->join('project_components', 'project_components.id_component', '=', 'sub_project_components.id_component')
                        ->join('sub_projects', function($join){
                            $join->on('sub_projects.id_project', '=', 'project_components.id_project')
                            ->on('sub_projects.id', '=', 'sub_project_components.id_sub_project');
                        })
                        ->join('sub_project_rona_awals', 'sub_project_rona_awals.id_sub_project_component', '=', 'sub_project_components.id')
                        ->where('project_components.id_project', $imp->id_project)
                        ->where('sub_project_rona_awals.id_rona_awal', $spr->id_rona_awal)
                        ->where('sub_project_components.id_component', $pc->id_component)->get();
                    $text = '<p><strong>Deskripsi '.$component->name.'</strong></p>';
                    $text .= $pc->description;
                    $text .= '<p><strong>Besaran</strong></p>';
                    $text .= "<p>$pc->measurement</p>";

                    $sub = '';
                    $ids = [];
                    foreach ($spcs as $s) {
                        $sub .= '<li><p><strong>'.$component->name.' pada '.$s['sub_project_name'].'</strong></p>'.
                        $s['description'].
                        '<p><strong>Besaran</strong></p>'.
                        '<p>'.$s['measurement'].'</p></li>';
                        $ids[] = $s['id'];
                    }

                    $text .= '<ol>'.$sub.'</ol>';
                    $pieA = PotentialImpactEvaluation::firstOrNew([
                        'id_impact_identification' => $imp->id,
                        'id_pie_param' => 1,
                    ]);
                    $pieA->text = $text;
                    $pieA->save();

                    $pra = ProjectRonaAwal::where('id',$imp->id_project_rona_awal)->first();
                    $ra = RonaAwal::where('id',$pra->id_rona_awal)->first();

                    $text = '<p><strong>Deskripsi '.$ra->name.'</strong></p>';
                    $text .= $pra->description;
                    $text .= '<p><strong>Besaran Dampak</strong></p>';
                    $text .='<p>'.$pra->measurement.'</p>';

                    $pieB = PotentialImpactEvaluation::firstOrNew([
                        'id_impact_identification' => $imp->id,
                        'id_pie_param' => 2,
                    ]);
                    $pieB->text = $text;
                    $pieB->save();

                    $pieC = PotentialImpactEvaluation::firstOrNew([
                        'id_impact_identification' => $imp->id,
                        'id_pie_param' => 3,
                    ]);
                    if (!$pieC->exists)
                    {
                        $pieC->text = null;
                        $pieC->save();
                    }

                    $pieD = PotentialImpactEvaluation::firstOrNew([
                        'id_impact_identification' => $imp->id,
                        'id_pie_param' => 4,
                    ]);

                    if (!$pieD->exists)
                    {
                        $pieD->text = null;
                        $pieD->save();
                    }

                    $spra = SubProjectRonaAwal::from('sub_project_rona_awals')
                      ->select(
                        'sub_project_rona_awals.id',
                        'sub_project_rona_awals.description_specific as description',
                        'sub_project_rona_awals.unit as measurement',
                        'sub_projects.name as sub_project_name'
                      )
                      ->join('sub_project_components', 'sub_project_components.id', '=', 'sub_project_rona_awals.id_sub_project_component')
                      ->join('sub_projects', 'sub_projects.id', '=', 'sub_project_components.id_sub_project')
                      ->whereIn('sub_project_rona_awals.id_sub_project_component', $ids)
                      ->where('sub_project_rona_awals.id_rona_awal', $spr->id_rona_awal)->get();

                    $text = '<p><strong>Deskripsi '.$ra->name.' terkait '.$component->name.'</strong></p>';
                    $sub = '';
                    foreach ($spra as $r) {
                        $sub .= '<li><p><strong>'.$r['sub_project_name'].'</strong></p>'.
                        $r['description'].
                        '<p><strong>Besaran</strong></p>'.
                        '<p>'.$r['measurement'].'</p></li>';
                    }
                    $text .='<ol>'.$sub.'</ol>';
                    $pieE = PotentialImpactEvaluation::firstOrNew([
                        'id_impact_identification' => $imp->id,
                        'id_pie_param' => 5,
                    ]);
                    $pieE->text = $text;
                    $pieE->save();
                }

                return response([
                    'id_sub_project_rona_awal' =>  $spr->id,
                    'id_impact_identification'=> $imp->id
                ], 200);
                // return response($spr->id, 200);
            }
        }
        return response(500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function show(SubProjectRonaAwal $subProjectRonaAwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function edit(SubProjectRonaAwal $subProjectRonaAwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubProjectRonaAwal $subProjectRonaAwal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubProjectRonaAwal $subProjectRonaAwal)
    {
        // try {
            $spc = SubProjectComponent::where('id', $subProjectRonaAwal->id_sub_project_component)->first();
            $sp = SubProject::where('id', $spc->id_sub_project)->first();
            $sSP = SubProject::where('id_project', $sp->id_project)
                    ->where('id', '<>', $sp->id)->get();

              $idSubProjects = [];
              foreach($sSP as $s){
                  $idSubProjects[] = $s->id;
              }

            $ospcra = SubProjectComponent::from('sub_project_components')
            ->select('sub_project_components.id as id_spc', 'sub_project_rona_awals.id as id_spra')
            ->join('sub_project_rona_awals', 'sub_project_rona_awals.id_sub_project_component', '=', 'sub_project_components.id' )
            ->where('sub_project_rona_awals.id_rona_awal', $subProjectRonaAwal->id_rona_awal)
            ->where('sub_project_components.id_component', $spc->id_component)
            ->whereIn('sub_project_components.id_sub_project', $idSubProjects)->get();

            if(count($ospcra) === 0){
                // delete imp
                $pc = ProjectComponent::where(['id_project' => $sp->id_project, 'id_component' => $spc->id_component])->first();
                $pra = ProjectRonaAwal::where(['id_project' => $sp->id_project, 'id_rona_awal' => $subProjectRonaAwal->id_rona_awal])->first();
                $imp = ImpactIdentification::where([
                    'id_project' => $sp->id_project,
                    'id_project_component' => $pc->id,
                    'id_project_rona_awal' => $pra->id
                ])->first();
                if($imp){
                    $pie = PotentialImpactEvaluation::where('id_impact_identification', $imp->id)->delete();
                    $imp->delete();
                }
            }

            return response($subProjectRonaAwal->delete(), 200);

        // } catch (\Exception $ex) {
        //    response()->json(['error' => $ex->getMessage()], 403);
        //}

        //return response()->json(null, 204);
    }
}
