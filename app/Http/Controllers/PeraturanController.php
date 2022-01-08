<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\KebijakanResource;
use App\Entity\Regulation;
use Validator;
use DB;
use Storage;

class PeraturanController extends Controller
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

        $regulations = Regulation::When($request->has('keyword'), function ($query) use ($request) {
            $searchQuery = '%' . $request->keyword . '%';
            $indents = $query->where('name', 'ILIKE', '%'.$request->keyword.'%');
            return $indents;
        })
        ->orderby('id', $sort ?? 'DESC')->paginate($request->limit ? $request->limit : 10);

        return response()->json($regulations, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            DB::beginTransaction();

            $peraturan = Regulation::create([
                'name' => $request->name,
            ]);

            if (!$peraturan) {
                DB::rollback();
            } else {
                DB::commit();
            }

            return new KebijakanResource($peraturan);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {

            $peraturan = Regulation::where('id', $request->id)->update([
                'name' => $request->name,
            ]);

            if ($peraturan) {
                return response()->json([
                    'message' => 'Berhasil mengubah data peraturan',
                    'status' => 200
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal mengubah data peraturan',
                'status' => 400
            ], 403);

        }
    }

    public function destroy($id)
    {
        try {
            $appParam = Regulation::where('id', $id);
            return $appParam->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }
    }
}
