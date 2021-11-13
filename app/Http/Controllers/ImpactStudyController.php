<?php

namespace App\Http\Controllers;

use App\Entity\ImpactStudy;
use App\Http\Resources\ImpactStudyResource;
use Illuminate\Http\Request;

class ImpactStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ImpactStudyResource::collection(ImpactStudy::all());
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
     * @param  \App\Entity\ImpactStudy  $impactStudy
     * @return \Illuminate\Http\Response
     */
    public function show(ImpactStudy $impactStudy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ImpactStudy  $impactStudy
     * @return \Illuminate\Http\Response
     */
    public function edit(ImpactStudy $impactStudy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ImpactStudy  $impactStudy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImpactStudy $impactStudy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ImpactStudy  $impactStudy
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImpactStudy $impactStudy)
    {
        //
    }
}
