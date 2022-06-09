<?php

namespace App\Http\Controllers;

use App\Entity\Business;
use App\Entity\BusinessEnvParam;
use App\Http\Resources\BusinessEnvParamResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessEnvParamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->businessTypePem){
            // return BusinessEnvParamResource::collection(BusinessEnvParam::select('business_env_params.id_param','business_env_params.id_unit','params.name as param', 'units.name as unit')->distinct()->where('business_id', $request->fieldId)->leftJoin('params', 'business_env_params.id_param', '=', 'params.id')->leftJoin('units', 'business_env_params.id_unit', '=', 'units.id')->get());
            
            return BusinessEnvParamResource::collection(BusinessEnvParam::leftJoin('business', 'business.id','=','business_env_params.business_id')->leftJoin('business as b2','business.parent_id','=','b2.id')->leftJoin('params', 'business_env_params.id_param', '=', 'params.id')->leftJoin('units', 'business_env_params.id_unit', '=', 'units.id')
            ->select('business_env_params.id_param','business_env_params.id_unit','params.name as param', 'units.name as unit')->distinct()
            ->where('business.value', $request->businessTypePem)->where('b2.value',$request->sectorName)->get());
        }
        if ($request->businessType) {
            if ($request->unit) {
                if ($request->unit == 'true') {
                    return BusinessEnvParamResource::collection(BusinessEnvParam::distinct()->where('business_id', $request->fieldId)->where('param', $request->businessType)->get(['unit']));
                }

                //get kbli env param by kbli_id, business_type and scale
                // if(gettype($request->fieldId) === 'string'){
                if($request->fieldName){
                    // return BusinessEnvParam::leftJoin('business', 'business.id','=','business_env_params.business_id')
                    // ->leftJoin('business as b2','business.parent_id','=','b2.id')
                    // ->where('business.value', $request->fieldId)
                    // ->where('b2.value',$request->sector)
                    // ->where('business_env_params.id_param', $request->businessType)
                    // ->where('business_env_params.id_unit', $request->unit)->toSql();
                    return BusinessEnvParamResource::collection(
                        BusinessEnvParam::leftJoin('business', 'business.id','=','business_env_params.business_id')
                        ->leftJoin('business as b2','business.parent_id','=','b2.id')
                        ->where('business.value', $request->fieldName)
                        ->where('b2.value',$request->sector)
                        ->where('business_env_params.id_param', $request->businessType)
                        ->where('business_env_params.id_unit', $request->unit)->get());
                }
                return BusinessEnvParamResource::collection(BusinessEnvParam::where('business_id', $request->fieldId)->where('id_param', $request->businessType)->where('id_unit', $request->unit)->get());
            } else if (isset($request->unit) && $request->unit == 0){
                //get kbli env param by kbli_id, business_type and scale
                if($request->fieldName){
                    // return BusinessEnvParam::leftJoin('business', 'business.id','=','business_env_params.business_id')
                    // ->leftJoin('business as b2','business.parent_id','=','b2.id')
                    // ->where('business.value', $request->fieldId)
                    // ->where('b2.value',$request->sector)
                    // ->where('business_env_params.id_param', $request->businessType)
                    // ->where('business_env_params.id_unit', $request->unit)->toSql();
                    return BusinessEnvParamResource::collection(
                        BusinessEnvParam::leftJoin('business', 'business.id','=','business_env_params.business_id')
                        ->leftJoin('business as b2','business.parent_id','=','b2.id')
                        ->where('business.value', $request->fieldName)
                        ->where('b2.value',$request->sector)
                        ->where('business_env_params.id_param', $request->businessType)
                        ->where('business_env_params.id_unit', $request->unit)->get());
                }
                //get kbli env param by kbli_id, business_type and scale
                return BusinessEnvParamResource::collection(BusinessEnvParam::where('business_id', $request->fieldId)->where('id_param', $request->businessType)->where('id_unit', $request->unit)->get());
            } 
            return BusinessEnvParamResource::collection(BusinessEnvParam::select('business_env_params.id_param','business_env_params.id_unit','params.name as param', 'units.name as unit')->distinct()->where('business_id', $request->fieldId)->leftJoin('params', 'business_env_params.id_param', '=', 'params.id')->leftJoin('units', 'business_env_params.id_unit', '=', 'units.id')->get());
        } else if ($request->page && $request->limit){
            return $this->getList($request);
        }
    }

    private function getList(Request $request)
    {
        $params = BusinessEnvParam::from('business_env_params AS bep')
            ->selectRaw('bep.id, p.name as param_name, condition, u.name as unit_name, doc_req, amdal_type, risk_level')
            ->leftJoin('params AS p', 'bep.id_param', '=', 'p.id')
            ->leftJoin('units AS u', 'bep.id_unit', '=', 'u.id')
            ->where(
                function ($query) use ($request) {
                    return $request->business_id ? 
                        $query->where('bep.business_id', '=', $request->business_id) : '';
                }
            )
            ->where(
                function ($query) use ($request) {
                    return $request->search ? 
                        $query->where('p.name', 'ilike', '%' . $request->search . '%') : '';
                }
            )
            ->paginate($request->limit);
        foreach ($params as $param) {
            $param->{"condition_string"} = $this->buildConditionString($param->condition);
        }
        return $params;
    }

    private function buildConditionString($condition) {
        $conditionArray = json_decode($condition);
        if (count($conditionArray) > 0) {
            return implode(', ', $conditionArray);
        } else {
            return '';
        }
    }

    private function createConditionJsonArray($condition_string)
    {
        $cStringArray = explode(', ', $condition_string);
        if (count($cStringArray) > 0) {
            return json_encode($cStringArray);
        } else {
            $cStringArray = explode(',', $condition_string);
            return json_encode($cStringArray);
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
        $businessEnvParam = $request->businessEnvParam;
        $businessEnvParam['condition'] = $this->createConditionJsonArray($businessEnvParam['condition_string']);
        $created = BusinessEnvParam::create([
            'business_id' => $businessEnvParam['business_id'],
            'id_param' => $businessEnvParam['id_param'],
            'condition' => $businessEnvParam['condition'],
            'id_unit' => $businessEnvParam['id_unit'],
            'doc_req' => $businessEnvParam['doc_req'],
            'amdal_type' => isset($businessEnvParam['amdal_type']) ? $businessEnvParam['amdal_type'] : null,
            'risk_level' => isset($businessEnvParam['risk_level']) ? $businessEnvParam['risk_level'] : null,
        ]);
        if ($created) {
            DB::commit();
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $created,
            ], 200);
        } else {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                'errors' => 'Failed to create BusinessEnvParam',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\BusinessEnvParam  $businessEnvParam
     * @return \Illuminate\Http\Response
     */
    public function show(BusinessEnvParam $businessEnvParam)
    {
        $obj = BusinessEnvParam::from('business_env_params AS bep')
            ->selectRaw('bep.id, bep.id_param, p.name AS param_name,
                bep.condition, bep.id_unit, u.name AS unit_name, bep.doc_req, bep.amdal_type, bep.risk_level')
            ->leftJoin('params AS p', 'bep.id_param', '=', 'p.id')
            ->leftJoin('units AS u', 'bep.id_unit', '=', 'u.id')
            ->where('bep.id', $businessEnvParam->id)
            ->first();
        $obj->{"condition_string"} = $this->buildConditionString($obj->condition);
        return $obj;
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
        $updatedParam = $request->businessEnvParam;
        $updatedParam['condition'] = $this->createConditionJsonArray($updatedParam['condition_string']);
        // assign value
        $businessEnvParam->id_param = $updatedParam['id_param'];
        $businessEnvParam->condition = $updatedParam['condition'];
        $businessEnvParam->id_unit = $updatedParam['id_unit'];
        $businessEnvParam->doc_req = $updatedParam['doc_req'];
        if ($businessEnvParam->doc_req == 'AMDAL' && !empty($updatedParam['amdal_type'])) {
            $businessEnvParam->amdal_type = $updatedParam['amdal_type'];
        } else if ($businessEnvParam->doc_req == 'UKL-UPL'&& !empty($updatedParam['risk_level'])) {
            $businessEnvParam->risk_level = $updatedParam['risk_level'];
        } else if ($businessEnvParam->doc_req == 'SPPL') {
            $businessEnvParam->amdal_type = null;
            $businessEnvParam->risk_level = 'Rendah';
        }
        DB::beginTransaction();
        try {
            $businessEnvParam->save();
            DB::commit();
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $businessEnvParam,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                // 'errors' => 'Failed to update Business',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\BusinessEnvParam  $businessEnvParam
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessEnvParam $businessEnvParam)
    {
        DB::beginTransaction();
        try {
            $businessEnvParam->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $businessEnvParam,
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
