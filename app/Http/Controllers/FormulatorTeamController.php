<?php

namespace App\Http\Controllers;

use App\Entity\FormulatorTeam;
use App\Http\Resources\FormulatorResource;
use Illuminate\Http\Request;

class FormulatorTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FormulatorResource::collection(FormulatorTeam::all());
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
     * @param  \App\Entity\FormulatorTeam  $formulatorTeam
     * @return \Illuminate\Http\Response
     */
    public function show(FormulatorTeam $formulatorTeam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\FormulatorTeam  $formulatorTeam
     * @return \Illuminate\Http\Response
     */
    public function edit(FormulatorTeam $formulatorTeam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\FormulatorTeam  $formulatorTeam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormulatorTeam $formulatorTeam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\FormulatorTeam  $formulatorTeam
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormulatorTeam $formulatorTeam)
    {
        //
    }
}
