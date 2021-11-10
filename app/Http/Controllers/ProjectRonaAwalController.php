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
        $all_params = $request->all();
        if (isset($all_params['rona_awals'])){
            $validator = $request->validate([
                'rona_awals' => 'required',
            ]);
            DB::beginTransaction();
            $num_created = 0;
            foreach ($validator['rona_awals'] as $rona_awal){
                $rona_awal['id'] == null;
                if ($rona_awal['id_rona_awal'] != null){
                    // only save id_rona_awal
                    $rona_awal['id_component_type'] = null;
                    $rona_awal['name'] = null;
                }
                if (ProjectRonaAwal::create($rona_awal)){
                    $num_created++;
                }
            }
            if ($num_created == count($validator['rona_awals'])){
                DB::commit();
                return response()->json(['code' => 200]);
            } else {
                DB::rollBack();
                return response()->json(['code' => 500]);
            }
        } else {
            $validator = $request->validate([
                'id_project' => 'required',
                'id_rona_awal' => 'required',
                'id_component_type' => 'required',
                'name' => 'required',
            ]);
            $validator['id'] = null;
            if ($validator['id_rona_awal'] != null){
                // only save id_rona_awal
                $validator['id_component_type'] = null;
                $validator['name'] = null;
            }
            DB::beginTransaction();
            if (ProjectRonaAwal::create($validator)){
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
