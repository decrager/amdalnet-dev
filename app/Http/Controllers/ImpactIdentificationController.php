<?php

namespace App\Http\Controllers;

use App\Entity\ImpactIdentification;
use App\Http\Resources\ImpactIdentificationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImpactIdentificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = ImpactIdentification::all();
        if ($request->id_project){
            $list = $list->where('id_project', $request->id_project);
        }
        if ($request->id_component){
            $list = $list->where('id_component', $request->id_component);
        }
        if ($request->id_rona_awal){
            $list = $list->where('id_rona_awal', $request->id_rona_awal);
        }
        return ImpactIdentificationResource::collection($list);
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
            'id_rona_awal' => 'required',
        ]);
        DB::beginTransaction();
        $created = ImpactIdentification::create($validator);
        if ($created){
            DB::commit();
        } else {
            DB::rollBack();
        }
        return new ImpactIdentificationResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ImpactIdentification  $impactIdentification
     * @return \Illuminate\Http\Response
     */
    public function show(ImpactIdentification $impactIdentification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ImpactIdentification  $impactIdentification
     * @return \Illuminate\Http\Response
     */
    public function edit(ImpactIdentification $impactIdentification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ImpactIdentification  $impactIdentification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImpactIdentification $impactIdentification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ImpactIdentification  $impactIdentification
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImpactIdentification $impactIdentification)
    {
        //
    }
}
