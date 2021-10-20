<?php

namespace App\Http\Controllers;

use App\Entity\Lpjp;
use App\Http\Resources\LpjpResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LpjpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LpjpResource::collection(Lpjp::all());
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
                'name'              => 'required',
                'pic'               => 'required',
                'reg_no'            => 'required',
                'address'           => 'required',
                'id_prov'           => 'required',
                'id_district'       => 'required',
                'mobile_phone_no'   => 'required',
                'email'             => 'required',
                'file'              => 'required',
                'contact_person'    => 'required',
                'phone_no'          => 'required',
                'url_address'       => 'required',
                'date_start'        => 'required',
                'date_end'          => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create file
            $file = $request->file('file');
            $name = '/lpjp/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            //create lpjp
            $lpjp = Lpjp::create([
                'name' => $params['name'],
                'pic' => $params['pic'],
                'reg_no' => $params['reg_no'],
                'address' => $params['address'],
                'id_prov' => $params['id_prov'],
                'id_district' => $params['id_district'],
                'mobile_phone_no' => $params['mobile_phone_no'],
                'email' => $params['email'],
                'cert_file' => Storage::url($name),
                'contact_person' => $params['contact_person'],
                'phone_no' => $params['phone_no'],
                'url_address' => $params['url_address'],
                'date_start' => $params['date_start'],
                'date_end' => $params['date_end'],
            ]);
            
            return new LpjpResource($lpjp);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\Lpjp  $lpjp
     * @return \Illuminate\Http\Response
     */
    public function show(Lpjp $lpjp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Lpjp  $lpjp
     * @return \Illuminate\Http\Response
     */
    public function edit(Lpjp $lpjp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Lpjp  $lpjp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lpjp $lpjp)
    {
        if ($lpjp === null) {
            return response()->json(['error' => 'lpjp not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name'              => 'required',
                'pic'               => 'required',
                'reg_no'            => 'required',
                'address'           => 'required',
                'id_prov'           => 'required',
                'id_district'       => 'required',
                'mobile_phone_no'   => 'required',
                'email'             => 'required',
                'file'              => 'required',
                'contact_person'    => 'required',
                'phone_no'          => 'required',
                'url_address'       => 'required',
                'date_start'        => 'required',
                'date_end'          => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            if($request->file('file') !== null){
                //create file
                $file = $request->file('file');
                $name = '/lpjp/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $lpjp->cert_file = Storage::url($name);
            }
            $lpjp->name = $params['name'];
            $lpjp->pic = $params['pic'];
            $lpjp->reg_no = $params['reg_no'];
            $lpjp->address = $params['address'];
            $lpjp->id_prov = $params['id_prov'];
            $lpjp->id_district = $params['id_district'];
            $lpjp->mobile_phone_no = $params['mobile_phone_no'];
            $lpjp->email = $params['email'];
            $lpjp->contact_person = $params['contact_person'];
            $lpjp->phone_no = $params['phone_no'];
            $lpjp->url_address = $params['url_address'];
            $lpjp->date_start = $params['date_start'];
            $lpjp->date_end = $params['date_end'];
            $lpjp->save();
        }

        return new LpjpResource($lpjp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Lpjp  $lpjp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lpjp $lpjp)
    {
        try {
            $lpjp->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    
        return response()->json(null, 204);
    }
}
