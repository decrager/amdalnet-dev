<?php

namespace App\Http\Controllers;

use App\Entity\BusinessEnvParam;
use App\Entity\KbliEnvParam;
use App\Http\Resources\BusinessEnvParamResource;
use App\Http\Resources\KbliEnvParamResource;
use Illuminate\Http\Request;

class BusinessEnvParamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->businessType) {
            if ($request->unit) {
                if ($request->unit == 'true') {
                    return BusinessEnvParamResource::collection(BusinessEnvParam::distinct()->where('business_id', $request->fieldId)->where('param', $request->businessType)->get(['unit']));
                }

                //get kbli env param by kbli_id, business_type and scale
                return BusinessEnvParamResource::collection(BusinessEnvParam::where('business_id', $request->fieldId)->where('id_param', $request->businessType)->where('id_unit', $request->unit)->get());
            } else if (isset($request->unit) && $request->unit == 0){
                //get kbli env param by kbli_id, business_type and scale
                return BusinessEnvParamResource::collection(BusinessEnvParam::where('business_id', $request->fieldId)->where('id_param', $request->businessType)->where('id_unit', $request->unit)->get());
            }
            return BusinessEnvParamResource::collection(BusinessEnvParam::select('business_env_params.id_param','business_env_params.id_unit','params.name as param', 'units.name as unit')->distinct()->where('business_id', $request->fieldId)->leftJoin('params', 'business_env_params.id_param', '=', 'params.id')->leftJoin('units', 'business_env_params.id_unit', '=', 'units.id')->get());
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
     * @param  \App\Entity\BusinessEnvParam  $businessEnvParam
     * @return \Illuminate\Http\Response
     */
    public function show(BusinessEnvParam $businessEnvParam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\BusinessEnvParam  $businessEnvParam
     * @return \Illuminate\Http\Response
     */
    public function edit(BusinessEnvParam $businessEnvParam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\BusinessEnvParam  $businessEnvParam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BusinessEnvParam $businessEnvParam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\BusinessEnvParam  $businessEnvParam
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessEnvParam $businessEnvParam)
    {
        //
    }
}
