<?php

namespace App\Http\Controllers;

use App\Entity\RonaAwal;
use App\Http\Resources\RonaAwalResource;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class RonaAwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if (isset($params['all']) && $params['all']) {
            return RonaAwalResource::collection(RonaAwal::select('rona_awal.*')
                ->orderBy('name', 'asc')
                ->get());
        }

        if (isset($params['q']) && $params['q']) {
            return RonaAwalResource::collection(
                RonaAwal::select(
                    'rona_awal.*',
                    'rona_awal.name as value'
                )
                ->where('name','ilike','%'.$request->q.'%')
                ->orderBy('name', 'ASC')
                ->limit(10)
                ->get());
        }
        if ((isset($params['id_component_type']) && $params['id_component_type']) &&
            (isset($params['project_id']) && $params['project_id'])) {
            return RonaAwalResource::collection(
                RonaAwal::select(
                    'rona_awal.*',
                    'rona_awal.name as value'
                )
                ->where('id_component_type', $request->id_component_type)
                ->where(function ($query) use ($request) {
                    $query->where('is_master', true);
                    $query->orWhere('originator_id', $request->project_id);
                    return $query;
                })
                ->orderBy('name', 'ASC')
                ->get());
        }

        return RonaAwal::select('rona_awal.*', 'components.name as component')->where(function ($query) use ($request) {
            return $request->document_type ? $query->where('result_risk', $request->document_type) : '';
        })->where(
            function ($query) use ($request) {
                return $request->idComponentType ? $query->where('idComponentType', $request->idComponentType) : '';
            }
        )->where(function($query) use($request) {
            if($request->search && ($request->search !== 'null')) {
                $query->where(function($q) use($request) {
                   $q->whereRaw("LOWER(rona_awal.name) LIKE '%" . strtolower($request->search) . "%'");
                })->orWhere(function($q) use($request) {
                    $q->whereRaw("LOWER(components.name) LIKE '%" . strtolower($request->search) . "%'");
                 });
            }
        })
        ->where( function ($query) use ($request) {
            if (isset($request->is_master) && ($request->is_master !== null)){
                return $query->where('rona_awal.is_master', $request->is_master);
            }
            return $query->where('rona_awal.is_master', true);
        })
        ->leftJoin('components', 'rona_awal.id_component_type', '=', 'components.id')->orderBy('rona_awal.id', 'DESC')->paginate($request->limit ? $request->limit : 10);

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
                'id_component_type'  => 'required',
                'name'               => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create rona awal
            $rona = RonaAwal::create([
                'id_component_type' => $params['id_component_type'],
                'name' => $params['name'],
            ]);

            return new RonaAwalResource($rona);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\RonaAwal  $ronaAwal
     * @return \Illuminate\Http\Response
     */
    public function show(RonaAwal $ronaAwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\RonaAwal  $ronaAwal
     * @return \Illuminate\Http\Response
     */
    public function edit(RonaAwal $ronaAwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\RonaAwal  $ronaAwal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RonaAwal $ronaAwal)
    {
        if ($ronaAwal === null) {
            return response()->json(['error' => 'rona awal not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'id_component_type'  => 'required',
                'name'               => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            $ronaAwal->id_component_type = $params['id_component_type'];
            $ronaAwal->name = $params['name'];
            $ronaAwal->save();
        }

        return new RonaAwalResource($ronaAwal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\RonaAwal  $ronaAwal
     * @return \Illuminate\Http\Response
     */
    public function destroy(RonaAwal $ronaAwal)
    {
        try {
            $ronaAwal->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
