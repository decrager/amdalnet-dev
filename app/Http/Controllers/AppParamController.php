<?php

namespace App\Http\Controllers;

use App\Entity\AppParam;
use App\Http\Resources\AppParamResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppParamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return AppParam::select('id', 'parameter_name', 'title', 'value')
        ->paginate($request->limit ? $request->limit : 10);

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
                'parameter_name'    => 'required',
                'title'             => 'required',
                'value'             => 'integer',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create App Param
            $appParam = AppParam::create([
                'parameter_name' => $params['parameter_name'],
                'title' => $params['title'],
                'value' => $params['value'],
                'is_numeric' => $params['is_numeric'],
            ]);

            return new AppParamResource($appParam);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\\AppParam  $appParam
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $detailAppParam = AppParam::where('parameter_name', $name)->get();
        return new AppParamResource($detailAppParam);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\\AppParam  $sop
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
     * @param  \App\Entity\\AppParam  $appParam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppParam $appParam)
    {
        if ($appParam === null) {
            return response()->json(['error' => 'App Param not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'parameter_name'    => 'required',
                'title'             => 'required',
                'value'             => 'integer',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $appParam->parameter_name = $params['parameter_name'];
            $appParam->title = $params['title'];
            $appParam->value = $params['value'];
            $appParam->is_numeric = $params['is_numeric'];
            $appParam->save();
        }

        return new AppParamResource($appParam);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\\AppParam  $sop
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $appParam = AppParam::where('id', $id);
            return $appParam->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }


    }
}
