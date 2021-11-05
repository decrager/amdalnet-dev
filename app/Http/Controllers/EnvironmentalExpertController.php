<?php

namespace App\Http\Controllers;

use App\Entity\EnvironmentalExpert;
use App\Http\Resources\EnvironmentalExpertResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EnvironmentalExpertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $experts = EnvironmentalExpert::orderBy('id', 'DESC')->paginate($request->limit);
        return EnvironmentalExpertResource::collection($experts);
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
                'status'            => 'required',
                'name'              => 'required',
                'expertise'         => 'required',
                'cv'                => 'required',
            ],[
                'status.required' => 'Status Peserta Belum Dipilih',
                'name.required' => 'Nama Tenaga Ahli Belum Diisi',
                'expertise.required' => 'Keahlian belum diisi'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 403);
        } else {
            $params = $request->all();

            //create file
            $file = $request->file('cv');
            $name = '/environmental-expert/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            //create environmental expert
            $expert = new EnvironmentalExpert();
            $expert->name = $params['name'];
            $expert->status = $params['status'];
            $expert->expertise = $params['expertise'];
            $expert->cv = Storage::url($name);
            $expert->save();
            
            return new EnvironmentalExpertResource($expert);
        }
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
        $validator = Validator::make(
            $request->all(),
            [
                'name'              => 'required',
                'status'            => 'required',
                'expertise'         => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $expert = EnvironmentalExpert::findOrFail($id);

            $params = $request->all();

            if($request->file('cv') !== null){
                //create file
                $file = $request->file('cv');
                $name = '/environmental-expert/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $expert->cv = Storage::url($name);
            }
            $expert->name = $params['name'];
            $expert->status = $params['status'];
            $expert->expertise = $params['expertise'];
            $expert->save();
        }

        return response()->json($expert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            EnvironmentalExpert::destroy($id);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    
        return response()->json(null, 204);
    }
}
