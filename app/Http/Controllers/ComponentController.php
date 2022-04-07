<?php

namespace App\Http\Controllers;

use App\Entity\Component;
use App\Http\Resources\ComponentResource;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class ComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if ($request->idProjectStage != null) {
        //     return ComponentResource::collection(Component::where('id_project_stage', $request->idProjectStage)->get());
        // } else {
        //     return ComponentResource::collection(Component::all());
        // }
        $params = $request->all();
        if (isset($params['all']) && $params['all']) {
            return ComponentResource::collection(Component::where('is_master', true)->get());
        }
        if (isset($params['q']) && $params['q']){
            // query
            return Component::select(
                'components.*',
                'components.name as value',
                'project_stages.name as project_stage_name'
                )
                ->where( function ($query) use ($request) {
                    $query->where('components.name', 'ilike', '%'.$request->q.'%');
                    if($request->idProjectStage){
                        $query->where('id_project_stage', '=', $request->idProjectStage);
                    }
                    return $query;
                })
                ->leftJoin('project_stages', 'components.id_project_stage', '=','project_stages.id')
                ->orderBy('components.name', 'ASC')
                ->limit(20)
                ->get();
        }

        if ((isset($params['stage_id']) && $params['stage_id']) &&
        (isset($params['project_id']) && $params['project_id'])) {
            return Component::select(
                'components.*',
                'components.name as value',
                'project_stages.name as project_stage_name'
                )
                ->where('id_project_stage', $request->stage_id)
                ->where( function ($query) use ($request) {
                    $query->where('components.is_master', true );
                    $query->orWhere('components.originator_id', $request->project_id);
                    return $query;
                })
                ->leftJoin('project_stages', 'components.id_project_stage', '=','project_stages.id')
                ->orderBy('components.name', 'ASC')
                ->get();
        }


        return Component::select('components.*', 'project_stages.name as project_stage')->where(function ($query) use ($request) {
            return $request->idProjectStage ? $query->where('id_project_stage', $request->idProjectStage) : '';
        })->where(function($query) use($request) {
            if($request->search && ($request->search !== 'null')) {
                $query->where(function($q) use($request) {
                    $q->whereRaw("LOWER(project_stages.name) LIKE '%" . strtolower($request->search) . "%'");
                })->orWhere(function($q) use($request) {
                    $q->whereRaw("LOWER(components.name) LIKE '%" . strtolower($request->search) . "%'");
                });
            }
        })->leftJoin('project_stages', 'components.id_project_stage', '=', 'project_stages.id')
        ->orderBy(($request->orderBy ? 'components.'.$request->orderBy : 'components.id'), ($request->orderBy ? $request->order : 'DESC'))
        ->paginate($request->limit ? $request->limit : 10);

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
        //validate request
        $validator = Validator::make(
            $request->all(),
            [
                'id_project_stage'  => 'required',
                'name'               => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create rona awal
            $component = Component::create([
                'id_project_stage' => $params['id_project_stage'],
                'name' => $params['name'],
            ]);

            return new ComponentResource($component);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show(Component $component)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit(Component $component)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Component $component)
    {
        if ($component === null) {
            return response()->json(['error' => 'Component not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'id_project_stage'  => 'required',
                'name'               => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            $component->id_project_stage = $params['id_project_stage'];
            $component->name = $params['name'];
            $component->save();
        }

        return new ComponentResource($component);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(Component $component)
    {
        try {
            $component->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
