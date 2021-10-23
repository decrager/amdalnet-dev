<?php

namespace App\Http\Controllers;

use App\Entity\ComponentType;
use App\Http\Resources\ComponentTypeResource;
use Illuminate\Http\Request;

class ComponentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ComponentTypeResource::collection(ComponentType::all());
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
     * @param  \App\Entity\ComponentType  $componentType
     * @return \Illuminate\Http\Response
     */
    public function show(ComponentType $componentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ComponentType  $componentType
     * @return \Illuminate\Http\Response
     */
    public function edit(ComponentType $componentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ComponentType  $componentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComponentType $componentType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ComponentType  $componentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComponentType $componentType)
    {
        //
    }
}
