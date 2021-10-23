<?php

namespace App\Http\Controllers;

use App\Entity\Sop;
use App\Http\Resources\SopResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SopResource::collection(Sop::all());
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
        //validate request

        $validator = Validator::make(
            $request->all(),
            [
                'id_component'          => 'required',
                'id_rona_awal'          => 'required',
                'mgmt_form'             => 'required',
                'mgmt_period'           => 'required',
                'monitoring_form'       => 'required',
                'monitoring_time'       => 'required',
                'monitoring_freq'       => 'required',
                'monitoring_date_field' => 'required',
                'name'                  => 'required',
                'impact'                => 'required',
                'other_impact'          => 'required',
                'monitoring_period'     => 'required',
                'impact_quantity'       => 'required',
                'code'                  => 'required',
                'effective_date'        => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create sop
            $sop = Sop::create([
                'id_component' => $params['id_component'],
                'id_rona_awal' => $params['id_rona_awal'],
                'mgmt_form' => $params['mgmt_form'],
                'mgmt_period' => $params['mgmt_period'],
                'monitoring_form' => $params['monitoring_form'],
                'monitoring_time' => $params['monitoring_time'],
                'monitoring_freq' => $params['monitoring_freq'],
                'monitoring_date_field' => $params['monitoring_date_field'],
                'name' => $params['name'],
                'impact' => $params['impact'],
                'other_impact' => $params['other_impact'],
                'monitoring_period' => $params['monitoring_period'],
                'impact_quantity' => $params['impact_quantity'],
                'code' => $params['code'],
                'effective_date' => $params['effective_date'],
            ]);
            
            return new SopResource($sop);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\\Sop  $sop
     * @return \Illuminate\Http\Response
     */
    public function show(Sop $sop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\\Sop  $sop
     * @return \Illuminate\Http\Response
     */
    public function edit(Sop $sop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\\Sop  $sop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sop $sop)
    {
        if ($sop === null) {
            return response()->json(['error' => 'SOP not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'id_component'          => 'required',
                'id_rona_awal'          => 'required',
                'mgmt_form'             => 'required',
                'mgmt_period'           => 'required',
                'monitoring_form'       => 'required',
                'monitoring_time'       => 'required',
                'monitoring_freq'       => 'required',
                'monitoring_date_field' => 'required',
                'name'                  => 'required',
                'impact'                => 'required',
                'other_impact'          => 'required',
                'monitoring_period'     => 'required',
                'impact_quantity'       => 'required',
                'code'                  => 'required',
                'effective_date'        => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            $sop->id_component = $params['id_component'];
            $sop->id_rona_awal = $params['id_rona_awal'];
            $sop->mgmt_form = $params['mgmt_form'];
            $sop->mgmt_period = $params['mgmt_period'];
            $sop->monitoring_form = $params['monitoring_form'];
            $sop->monitoring_time = $params['monitoring_time'];
            $sop->monitoring_freq = $params['monitoring_freq'];
            $sop->monitoring_date_field = $params['monitoring_date_field'];
            $sop->name = $params['name'];
            $sop->impact = $params['impact'];
            $sop->other_impact = $params['other_impact'];
            $sop->monitoring_period = $params['monitoring_period'];
            $sop->impact_quantity = $params['impact_quantity'];
            $sop->code = $params['code'];
            $sop->effective_date = $params['effective_date'];
            $sop->save();
        }

        return new SopResource($sop);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\\Sop  $sop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sop $sop)
    {
        try {
            $sop->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    
        return response()->json(null, 204);
    }
}
