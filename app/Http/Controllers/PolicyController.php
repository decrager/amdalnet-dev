<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\KebijakanResource;
use App\Entity\Policy;
use Validator;
use DB;
use Storage;

class PolicyController extends Controller
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

        $regulations = Policy::with(['regulation'])->when($request->has('keyword'), function ($query) use ($request) {
            $columnsToSearch = ['about', 'set', 'field_of_activity'];
            $searchQuery = '%' . $request->keyword . '%';
            $indents = $query->where('regulation_no', 'ILIKE', '%'.$request->keyword.'%');
            foreach($columnsToSearch as $column) {
                $indents = $indents->orWhere($column, 'ILIKE', $searchQuery);
            }

            return $indents;
        })
        ->when($request->has('type'), function ($query) use ($request) {

            $indents = $query->where('regulation_type', '=', $request->type);

            return $indents;
        })
        ->orderby('id', $sort ?? 'DESC')->paginate($request->limit ? $request->limit : 10);

        return response()->json($regulations, 200);
    }

    public function store(Request $request)
    {
        //validate request
        $validator = Validator::make(
            $request->all(),
            [
                'regulation_no' => 'required',
                'regulation_type' => 'required',
                'about' => 'required',
                'set' => 'required',
                'link' => 'required',
                'field_of_activity' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create file
            $file = $request->file('link');
            $name = '/kebijakan/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            DB::beginTransaction();

            //create Kebijakan
            $kebijakan = Policy::create([
                'regulation_no' => $request->regulation_no,
                'regulation_type' => $request->regulation_type,
                'about' => $request->about,
                'set' => $request->set,
                'link' => Storage::url($name),
                'field_of_activity' => $request->field_of_activity,
            ]);


            if (!$kebijakan) {
                DB::rollback();
            } else {
                DB::commit();
            }

            return new KebijakanResource($kebijakan);
        }
    }

    public function update(Request $request)
    {

        //validate request
        $validator = Validator::make(
            $request->all(),
            [
                'regulation_no' => 'required',
                'regulation_type' => 'required',
                'about' => 'required',
                'set' => 'required',
                'field_of_activity' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {

            //create file
            if(!empty($request->file('link'))) {
                $file = $request->file('link');
                $name = '/kebijakan/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $name = Storage::url($name);
            } else {
                $name = $request->old_link;
            }


            //create Kebijakan
            $kebijakan = Policy::where('id', $request->id)->update([
                'regulation_no' => $request->regulation_no,
                'regulation_type' => $request->regulation_type,
                'about' => $request->about,
                'set' => $request->set,
                'link' => $name,
                'field_of_activity' => $request->field_of_activity,
            ]);

            if ($kebijakan) {
                return response()->json([
                    'message' => 'Berhasil mengubah data kebijkakan',
                    'status' => 200
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal mengubah data kebijkakan',
                'status' => 400
            ], 403);


        }
    }

    public function destroy($id)
    {
        try {
            $appParam = Policy::where('id', $id);
            return $appParam->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }
    }
}
