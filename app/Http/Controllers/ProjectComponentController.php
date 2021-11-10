<?php

namespace App\Http\Controllers;

use App\Entity\ProjectComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $all_params = $request->all();
        if (isset($all_params['components'])){
            $validator = $request->validate([
                'components' => 'required',
            ]);
            DB::beginTransaction();
            $num_created = 0;
            foreach ($validator['components'] as $component){
                $component['id'] == null;
                if ($component['id_component'] != null){
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
        //
    }
}
