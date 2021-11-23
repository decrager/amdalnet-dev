<?php

namespace App\Http\Controllers;

use App\Entity\SubProjectRonaAwal;
use Illuminate\Http\Request;

class SubProjectRonaAwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function show(SubProjectRonaAwal $subProjectRonaAwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function edit(SubProjectRonaAwal $subProjectRonaAwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubProjectRonaAwal $subProjectRonaAwal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\SubProjectRonaAwal  $subProjectRonaAwal
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubProjectRonaAwal $subProjectRonaAwal)
    {
        try {
            $subProjectRonaAwal->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
