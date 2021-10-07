<?php

namespace App\Http\Controllers;

use App\Entity\ProjectField;
use App\Http\Resources\ProjectFieldResource;
use Illuminate\Http\Request;

class ProjectFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProjectFieldResource::collection(ProjectField::all());
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
     * @param  \App\Entity\ProjectField  $projectField
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectField $projectField)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectField  $projectField
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectField $projectField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectField  $projectField
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectField $projectField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectField  $projectField
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectField $projectField)
    {
        //
    }
}
