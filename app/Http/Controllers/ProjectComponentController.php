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
        $validator = $request->validate([
            'id_project' => 'required',
            'id_component' => 'required',
            'id_project_stage' => 'required',
            'name' => 'required',
        ]);

        if ($validator['id_component'] != null){
            // only save id_component
            $validator['id_project_stage'] = null;
            $validator['name'] = null;
        }
        DB::beginTransaction();
        if (ProjectComponent::create($validator)){
            DB::commit();
        } else {
            DB::rollBack();
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
