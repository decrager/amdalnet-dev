<?php

namespace App\Http\Controllers;

use App\Entity\RonaAwal;
use App\Http\Resources\RonaAwalResource;
use Illuminate\Http\Request;

class RonaAwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->idComponentType != null){
            return RonaAwalResource::collection(RonaAwal::where('id_component_type', $request->idComponentType)->get());
          } else {
            return RonaAwalResource::collection(RonaAwal::all());
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
     * @param  \App\Entity\RonaAwal  $ronaAwal
     * @return \Illuminate\Http\Response
     */
    public function show(RonaAwal $ronaAwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\RonaAwal  $ronaAwal
     * @return \Illuminate\Http\Response
     */
    public function edit(RonaAwal $ronaAwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\RonaAwal  $ronaAwal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RonaAwal $ronaAwal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\RonaAwal  $ronaAwal
     * @return \Illuminate\Http\Response
     */
    public function destroy(RonaAwal $ronaAwal)
    {
        //
    }
}
