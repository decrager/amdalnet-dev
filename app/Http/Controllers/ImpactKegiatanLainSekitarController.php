<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entity\ImpactKegiatanLainSekitar;
use App\Entity\KegiatanLainSekitar;

class ImpactKegiatanLainSekitarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // id
        if(!isset($request->id)){
            return response('Tidak bisa mengambil data', 416);
        }
        $res = KegiatanLainSekitar::from('kegiatan_lain_sekitars')
         ->select(
            'kegiatan_lain_sekitars.id',
            'kegiatan_lain_sekitars.name',
            'kegiatan_lain_sekitars.name as value',
            'kegiatan_lain_sekitars.is_master',
            'kegiatan_lain_sekitars.originator_id',
            'project_kegiatan_lain_sekitars.id as id_project_kegiatan_lain_sekitar',
            'project_kegiatan_lain_sekitars.description',
            'project_kegiatan_lain_sekitars.measurement',
            'project_kegiatan_lain_sekitars.address',
            'project_kegiatan_lain_sekitars.province_id',
            'project_kegiatan_lain_sekitars.district_id',
            'provinces.name as province_name',
            'districts.name as district_name',
         )
         ->join('project_kegiatan_lain_sekitars', 'project_kegiatan_lain_sekitars.kegiatan_lain_sekitar_id', '=', 'kegiatan_lain_sekitars.id')
         ->join('impact_kegiatan_lain_sekitars', 'impact_kegiatan_lain_sekitars.id_project_kegiatan_lain_sekitar', '=', 'project_kegiatan_lain_sekitars.id')
         ->leftJoin('provinces', 'provinces.id', '=', 'project_kegiatan_lain_sekitars.province_id')
         ->leftJoin('districts', 'districts.id', '=', 'project_kegiatan_lain_sekitars.district_id')
         ->where('impact_kegiatan_lain_sekitars.id_impact_identification', $request->id)->get();

         return response($res, 200);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
