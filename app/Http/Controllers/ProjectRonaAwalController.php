<?php

namespace App\Http\Controllers;

use App\Entity\ProjectRonaAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectRonaAwalController extends Controller
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
            'id_rona_awal' => 'required',
            'id_component_type' => 'required',
            'name' => 'required',
        ]);

        if ($validator['id_rona_awal'] != null){
            // only save id_rona_awal
            $validator['id_component_type'] = null;
            $validator['name'] = null;
        }
        DB::beginTransaction();
        if (ProjectRonaAwal::create($validator)){
            DB::commit();
        } else {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ProjectRonaAwal  $projectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectRonaAwal $projectRonaAwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ProjectRonaAwal  $projectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectRonaAwal $projectRonaAwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ProjectRonaAwal  $projectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectRonaAwal $projectRonaAwal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ProjectRonaAwal  $projectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectRonaAwal $projectRonaAwal)
    {
        //
    }
}
