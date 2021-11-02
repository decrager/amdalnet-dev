<?php

namespace App\Http\Controllers;

use App\EntityHome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo Storage::url('tes.jpg');
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
     * @param  \App\EntityHome  $entityHome
     * @return \Illuminate\Http\Response
     */
    public function show(EntityHome $entityHome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EntityHome  $entityHome
     * @return \Illuminate\Http\Response
     */
    public function edit(EntityHome $entityHome)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EntityHome  $entityHome
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntityHome $entityHome)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EntityHome  $entityHome
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntityHome $entityHome)
    {
        //
    }
}
