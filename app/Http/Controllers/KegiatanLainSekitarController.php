<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\KegiatanLainSekitarResource;
use App\Entity\KegiatanLainSekitar;

class KegiatanLainSekitarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        return KegiatanLainSekitarResource::collection(
            KegiatanLainSekitar::select('id', 'name', 'name as value', 'is_master', 'originator_id')
            ->where(function($q) use ($request) {
                $q->where('is_master', true);
                if($request->id_project){
                    $q->orWhere('originator_id', $request->id_project);
                }
                return $q;
            })->get());
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
        // id, name
        $params = $request->all();
        $kls = null;
        if($request->id){
            $kls = KegiatanLainSekitar::find($request->id);
        }else{
            $kls = KegiatanLainSekitar::create([
                'name' => $request->name,
                'is_master' => $request->is_master,
                'originator_id' => $request->id_project
            ]);
        }
        return response($kls, 200);

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
