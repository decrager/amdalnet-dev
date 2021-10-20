<?php

namespace App\Http\Controllers;

use App\Entity\ResponderType;
use App\Http\Resources\ResponderTypeResource;
use Illuminate\Http\Request;

class ResponderTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ResponderTypeResource::collection(ResponderType::all());
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
     * @param  \App\Entity\ResponderType  $responderType
     * @return \Illuminate\Http\Response
     */
    public function show(ResponderType $responderType)
    {
        return $responderType;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ResponderType  $responderType
     * @return \Illuminate\Http\Response
     */
    public function edit(ResponderType $responderType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ResponderType  $responderType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResponderType $responderType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ResponderType  $responderType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResponderType $responderType)
    {
        //
    }
}
