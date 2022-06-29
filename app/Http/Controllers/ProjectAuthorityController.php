<?php

namespace App\Http\Controllers;

use App\Entity\ProjectAuthority;
use Illuminate\Http\Request;

class ProjectAuthorityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->idProject){
            return ProjectAuthority::where('id_project',$request->idProject)->get();
        }
        return $request;
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
     * @param  \App\Entity\ProjectAuthority  $projectAuthority
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectAuthority $projectAuthority)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectAuthority  $projectAuthority
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectAuthority $projectAuthority)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectAuthority  $projectAuthority
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectAuthority $projectAuthority)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectAuthority  $projectAuthority
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectAuthority $projectAuthority)
    {
        //
    }
}
