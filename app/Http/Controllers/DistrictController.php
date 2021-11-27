<?php

namespace App\Http\Controllers;

use App\Entity\District;
use App\Entity\Province;
use App\Http\Resources\DistrictResource;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->idProv != null){
          return DistrictResource::collection(District::where('id_prov', $request->idProv)->get());
        } else if($request->provName != null) {
            $province = Province::where('name', $request->provName)->first();

            if($province) {
                return DistrictResource::collection(District::where('id_prov', $province->id)->get());
            }
            return DistrictResource::collection(new District());
        } else {
          return DistrictResource::collection(District::all());
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
     * @param  \App\Entity\District  $district
     * @return \Illuminate\Http\Response
     */
    public function show(District $district)
    {
        return $district;
    }

    public function showByProvince($id)
    {
        return District::where('id_prov', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\District  $district
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\District  $district
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, District $district)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\District  $district
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district)
    {
        //
    }
}
