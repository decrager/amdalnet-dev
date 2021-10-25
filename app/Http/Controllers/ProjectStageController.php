<?php

namespace App\Http\Controllers;

use App\Entity\ProjectStage;
use App\Http\Resources\ProjectStageResource;
use Illuminate\Http\Request;

class ProjectStageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProjectStageResource::collection(ProjectStage::all());
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
     * @param  \App\Entity\ProjectStage  $projectStage
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectStage $projectStage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectStage  $projectStage
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectStage $projectStage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectStage  $projectStage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectStage $projectStage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectStage  $projectStage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectStage $projectStage)
    {
        //
    }
}
