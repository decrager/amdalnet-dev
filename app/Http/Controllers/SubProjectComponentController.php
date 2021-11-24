<?php

namespace App\Http\Controllers;

use App\Entity\SubProjectComponent;
use App\Http\Resources\SubProjectComponentResource;
use Illuminate\Http\Request;

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
                'components.id_project_stage AS id_project_stage_master')
                ->leftJoin('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
                ->leftJoin('projects', 'sub_projects.id_project', '=', 'projects.id')
                ->leftJoin('components', 'sub_project_components.id_component', '=', 'components.id')
                ->where('sub_projects.id_project', $params['id_project'])
                ->get();
            return SubProjectComponentResource::collection($components);
        } else {
            return SubProjectComponentResource::collection(SubProjectComponent::with('component')->get()); 
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\SubProjectComponent  $subProjectComponent
     * @return \Illuminate\Http\Response
     */
    public function show(SubProjectComponent $subProjectComponent)
    {
        //
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
            $subProjectComponent->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
