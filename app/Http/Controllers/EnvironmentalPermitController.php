<?php

namespace App\Http\Controllers;

use App\Entity\EnvironmentalPermit;
use App\Http\Resources\EnvironmentalExpertResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EnvironmentalPermitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->sort == 'null') {
            $sort = 'DESC';
        } else {
            $sort = $request->sort;
        }

        if($request->search && ($request->search !== 'null')) {
            $environmentalPermit = EnvironmentalPermit::where(function($q) use($request) {
                $q->where(function($query) use($request) {
                    $query->whereRaw("LOWER(pemarkasa_name) LIKE '%" . strtolower($request->search) . "%'");
                })->orWhere(function($query) use($request) {
                    $query->whereRaw("LOWER(kegiatan_name) LIKE '%" . strtolower($request->search) . "%'");
                })->orWhere(function($query) use($request) {
                    $query->whereRaw("LOWER(sk_number) LIKE '%" . strtolower($request->search) . "%'");
                });
            })->orderby($request->orderBy ?? 'date', $sort ?? 'DESC')->paginate($request->limit ? $request->limit : 10);
    
            return response()->json($environmentalPermit, 200);
        } else {
            $environmentalPermit = EnvironmentalPermit::When($request->has('keyword'), function ($query) use ($request) {
                if($request->keyword) {
                    $columnsToSearch = ['authority', 'kegiatan_name', 'sk_number', 'publisher'];
                    $searchQuery = '%' . $request->keyword . '%';
                    $indents = $query->where('pemarkasa_name', 'ILIKE', '%'.$request->keyword.'%');
                    foreach($columnsToSearch as $column) {
                        $indents = $indents->orWhere($column, 'ILIKE', $searchQuery);
                    }
                    return $indents;
                }
    
            })->orderby($request->orderBy ?? 'date', $sort ?? 'DESC')->paginate($request->limit ? $request->limit : 10);
    
            return response()->json($environmentalPermit, 200);
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
        $validator = Validator::make(
            $request->all(),
            [
                'pemarkasa_name'    => 'required',
                'authority'         => 'required',
                'kegiatan_name'     => 'required',
                'sk_number'         => 'required',
                'date'              => 'required',
                'publisher'         => 'required',
                'document_type'     => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 403);
        } else {
            $params = $request->all();

            //create file
            if (!empty($request->file('file'))) {
                $file = $request->file('file');
                $name = 'environmental-permit/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
            } else {
                $name = NULL;
            }
            

            //create environmental permits
            $permit = new EnvironmentalPermit();
            $permit->pemarkasa_name = $params['pemarkasa_name'];
            $permit->authority = $params['authority'];
            $permit->kegiatan_name = $params['kegiatan_name'];
            $permit->sk_number = $params['sk_number'];
            $permit->date = $params['date'];
            $permit->publisher = $params['publisher'];
            $permit->file = $name;
            $permit->document_type = $params['document_type'];
            $permit->save();

            return new EnvironmentalExpertResource($permit);
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
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'pemarkasa_name'    => 'required',
                'authority'         => 'required',
                'kegiatan_name'     => 'required',
                'sk_number'         => 'required',
                'date'              => 'required',
                'publisher'         => 'required',
                'document_type'     => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $permit = EnvironmentalPermit::findOrFail($request->id);

            $params = $request->all();

            if($request->hasFile('file')){
                //create file
                $file = $request->file('file');
                $name = 'environmental-permit/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $permit->file = $name;
            } 

            $permit->pemarkasa_name = $params['pemarkasa_name'];
            $permit->authority = $params['authority'];
            $permit->kegiatan_name = $params['kegiatan_name'];
            $permit->sk_number = $params['sk_number'];
            $permit->date = $params['date'];
            $permit->publisher = $params['publisher'];
            $permit->document_type = $params['document_type'];
            $permit->save();
        }

        return response()->json($permit);
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
            EnvironmentalPermit::destroy($id);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
