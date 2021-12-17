<?php

namespace App\Http\Controllers;

use App\Entity\EnvMonitorPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        //
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
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
