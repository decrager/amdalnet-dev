<?php

namespace App\Http\Controllers;

use App\Entity\ImpactIdentification;
use App\Http\Resources\ImpactIdentificationResource;
use Illuminate\Http\Request;

class ImpactIdentificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ImpactIdentificationResource::collection(ImpactIdentification::all());
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
