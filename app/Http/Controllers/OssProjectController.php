<?php

namespace App\Http\Controllers;

use App\Entity\OssProject;
use App\Http\Resources\OssProjectResource;
use Illuminate\Http\Request;
use DB;

class OssProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return OssProjectResource::Collection(OssProject::all());
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
     * @param  \App\Entity\OssProject  $ossProject
     * @return \Illuminate\Http\Response
     */
    public function show(OssProject $ossProject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\OssProject  $ossProject
     * @return \Illuminate\Http\Response
     */
    public function edit(OssProject $ossProject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\OssProject  $ossProject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OssProject $ossProject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\OssProject  $ossProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(OssProject $ossProject)
    {
        //
    }
}
