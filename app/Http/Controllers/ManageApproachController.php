<?php

namespace App\Http\Controllers;

use App\Entity\EnvManageApproach;
use Illuminate\Http\Request;

class ManageApproachController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $technology = EnvManageApproach::where([['id_project', $request->idProject],['approach_type', 'technology']])->get();
        $social_economy = EnvManageApproach::where([['id_project', $request->idProject],['approach_type', 'social_economy']])->get();
        $institution = EnvManageApproach::where([['id_project', $request->idProject], ['approach_type', 'institution']])->get();

        return [
            'technology' => $technology,
            'social_economy' => $social_economy,
            'institution' => $institution
        ];
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
        $approach = new EnvManageApproach();
        $approach->id_project = $request->idProject;
        $approach->approach_type = $request->type;
        $approach->description = $request->description;
        $approach->save();

        return $approach;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
