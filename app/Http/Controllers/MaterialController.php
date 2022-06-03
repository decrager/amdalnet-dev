<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entity\Material;
use App\Http\Resources\KebijakanResource;
use Validator;
use DB;
use Storage;

class MaterialController extends Controller
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
            $materials = Material::where(function($q) use($request) {
                $q->where(function($query) use($request) {
                    $query->whereRaw("LOWER(name) LIKE '%" . strtolower($request->search) . "%'");
                })->orWhere(function($query) use($request) {
                    $query->whereRaw("LOWER(description) LIKE '%" . strtolower($request->search) . "%'");
                });
            })->orderby('id', 'desc')->paginate($request->limit ? $request->limit : 10);
    
            return response()->json($materials, 200);
        } else {
            $materials = Material::When($request->has('keyword'), function ($query) use ($request) {
    
                $searchQuery = '%' . $request->keyword . '%';
                $indents = $query->where('name', 'ILIKE', '%'.$request->keyword.'%');
                $indents = $indents->orWhere('description', 'ILIKE', $searchQuery);
    
                return $indents;
            })->orderby('id', $sort ?? 'DESC')->paginate($request->limit ? $request->limit : 10);
    
            return response()->json($materials, 200);
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
        //validate request
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'raise_date' => 'required',
                'link' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create file
            $file = $request->file('link');
            $name = 'materi/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            DB::beginTransaction();

            //create Kebijakan
            $materi = Material::create([
                'name' => $request->name,
                'description' => $request->description,
                'raise_date' => $request->raise_date,
                'link' => $name,
            ]);


            if (!$materi) {
                DB::rollback();
            } else {
                DB::commit();
            }

            return new KebijakanResource($materi);
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
         //validate request
         $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'raise_date' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $update = [
                'name' => $request->name,
                'description' => $request->description,
                'raise_date' => $request->raise_date
            ];

            //create file
            if(!empty($request->file('link'))) {
                $file = $request->file('link');
                $name = 'materi/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $update['link'] = $name;
            }


            //create Kebijakan
            $materi = Material::where('id', $request->id)->update($update);

            if ($materi) {
                return response()->json([
                    'message' => 'Berhasil mengubah data materi',
                    'status' => 200
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal mengubah data materi',
                'status' => 400
            ], 403);


        }
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
            $appParam = Material::where('id', $id);
            return $appParam->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }
    }
}
