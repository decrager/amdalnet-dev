<?php

namespace App\Http\Controllers;

use App\Entity\Authority;
use Illuminate\Http\Request;

class AuthorityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $request->sectors;
        if($request->sectors){
            return Authority::where('sector', $request->sectors)->get();
        }
        return Authority::distinct()->get(['sector']);
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
     * @param  \App\Entity\Authority  $authority
     * @return \Illuminate\Http\Response
     */
    public function show(Authority $authority)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Authority  $authority
     * @return \Illuminate\Http\Response
     */
    public function edit(Authority $authority)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Authority  $authority
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Authority $authority)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Authority  $authority
     * @return \Illuminate\Http\Response
     */
    public function destroy(Authority $authority)
    {
        //
    }
}
