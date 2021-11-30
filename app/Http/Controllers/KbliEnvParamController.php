<?php

namespace App\Http\Controllers;

use App\Entity\KbliEnvParam;
use App\Http\Resources\KbliEnvParamResource;
use Illuminate\Http\Request;
use DB;

class KbliEnvParamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->kbli) {
            $kbliName = $request->kbli;

            $kbli = DB::table('kblis')->where('value', $kbliName)->first();

            if ($kbli != null) {

                if ($request->businessType) {
                    if ($request->unit) {
                        if ($request->unit == 'true') {
                            return KbliEnvParamResource::collection(KbliEnvParam::distinct()->where('kbli_id', $kbli->id)->where('param', $request->businessType)->get(['unit']));
                        }
                        
                        //get kbli env param by kbli_id, business_type and scale
                        return KbliEnvParamResource::collection(KbliEnvParam::where('kbli_id', $kbli->id)->where('param',$request->businessType)->where('unit',$request->unit)->get());
                    }
                    return KbliEnvParamResource::collection(KbliEnvParam::distinct()->where('kbli_id', $kbli->id)->get(['param','unit']));
                }
            } else {
                return response()->json(['error' => 'kbli ' + $request->kbli + ' not found'], 404);
            }
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
     * @param  \App\Entity\KbliEnvParam  $kbliEnvParam
     * @return \Illuminate\Http\Response
     */
    public function show(KbliEnvParam $kbliEnvParam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\KbliEnvParam  $kbliEnvParam
     * @return \Illuminate\Http\Response
     */
    public function edit(KbliEnvParam $kbliEnvParam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\KbliEnvParam  $kbliEnvParam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KbliEnvParam $kbliEnvParam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\KbliEnvParam  $kbliEnvParam
     * @return \Illuminate\Http\Response
     */
    public function destroy(KbliEnvParam $kbliEnvParam)
    {
        //
    }
}
