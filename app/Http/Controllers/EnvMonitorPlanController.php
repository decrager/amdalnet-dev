<?php

namespace App\Http\Controllers;

use App\Entity\EnvMonitorPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EnvMonitorPlanController extends Controller
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
        $validator = Validator::make(
            $request->all(),
            [
                'id_impact_identifications' => 'required',
                'form' => 'required',
            ]
        ); 
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $validator->validated();
            DB::beginTransaction();
            $saved = EnvMonitorPlan::create($params);
            if ($saved->id > 0) {
                DB::commit();
            } else {
                DB::rollBack();
            }
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $saved,
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\EnvMonitorPlan  $envMonitorPlan
     * @return \Illuminate\Http\Response
     */
    public function show(EnvMonitorPlan $envMonitorPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\EnvMonitorPlan  $envMonitorPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(EnvMonitorPlan $envMonitorPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\EnvMonitorPlan  $envMonitorPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnvMonitorPlan $envMonitorPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\EnvMonitorPlan  $envMonitorPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnvMonitorPlan $envMonitorPlan)
    {
        DB::beginTransaction();
        try {
            $envMonitorPlan->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $envMonitorPlan,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
