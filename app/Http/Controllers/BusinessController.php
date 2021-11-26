<?php

namespace App\Http\Controllers;

use App\Entity\Business;
use App\Http\Resources\BusinessResource;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->sectors){
            return BusinessResource::collection(Business::distinct()->where('description', 'sector')->get(['value']));
          } else if ($request->sectorsByKbli) {
            $kbliName = $request->sectorsByKbli;

            $kbli = DB::table('kblis')->where('value', $kbliName)->first();

            if($kbli != null){
                return BusinessResource::collection(Business::distinct()->where('description', 'sector')->where('parent_id', $kbli->id)->get(['value']));
            } else {
                return response()->json(['error' => 'kbli '+ $request->sectorsByKbli +' not found'], 404);
            }
          } else if ($request->fieldByKbli) {
            $kbliName = $request->fieldByKbli;

            $kbli = DB::table('kblis')->where('value', $kbliName)->first();

            if($kbli != null){
                return BusinessResource::collection(Business::where('description', 'field')->where('parent_id', $kbli->id)->get(['id','value']));
            } else {
                return response()->json(['error' => 'kbli '+ $request->fieldByKbli +' not found'], 404);
            }
          } else if ($request->kblis) {
            return BusinessResource::collection(Business::distinct()->where('description', 'kbli_code')->get(['value']));
          } else {
            return BusinessResource::collection(Business::all());
          }
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
     * @param  \App\Entity\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Business $business)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        //
    }
}
