<?php

namespace App\Http\Controllers;

use App\Entity\ChangeType;
use App\Http\Resources\ChangeTypeResource;
use Illuminate\Http\Request;

class ChangeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        return ChangeTypeResource::collection(ChangeType::where('is_primary', true)
            ->orderBy('id', 'asc')
            ->get());*/

        return ChangeTypeResource::collection(ChangeType::select('change_types.*')
            ->orderBy('id', 'asc')
            ->get());


    }

    /**
     * Restful API for adding new ChangeType.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addChangeType(Request $request)
    {

        $ctype = ChangeType::firstOrCreate(['name' => $request->name]);
        if(!$ctype) return response('cannot create new ChangeType', 418);

        return response()->json([ 'code' => 200,'data' => $ctype ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
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
     * @param  \App\Entity\ChangeType  $changeType
     * @return \Illuminate\Http\Response
     */
    public function show(ChangeType $changeType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ChangeType  $changeType
     * @return \Illuminate\Http\Response
     */
    public function edit(ChangeType $changeType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ChangeType  $changeType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChangeType $changeType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ChangeType  $changeType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChangeType $changeType)
    {
        //
    }
}
