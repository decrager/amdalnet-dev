<?php

namespace App\Http\Controllers;

use App\Entity\Business;
use App\Http\Resources\BusinessResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->sectors){
            return BusinessResource::collection(Business::distinct()->where('description', 'sector')->get(['value']));
          } else if ($request->sectorsByKbli) {
            $kbliName = $request->sectorsByKbli;

            $kbli = DB::table('business')->where('value', $kbliName)->first();

            if($kbli != null){
                return BusinessResource::collection(Business::distinct()->where('description', 'sector')->where('parent_id', $kbli->id)->get());
            } else {
                return response()->json(['error' => 'kbli '+ $request->sectorsByKbli +' not found'], 404);
            }
          } else if ($request->fieldBySector) {
                return BusinessResource::collection(Business::where('description', 'field')->where('parent_id', $request->fieldBySector)->get(['id','value']));
          } else if ($request->kblis) {
            return BusinessResource::collection(Business::distinct()->where('description', 'kbli_code')->get(['value']));
          } else if ($request->page && $request->limit){
            return $this->getList($request);
          } else {
            return BusinessResource::collection(Business::all());
          }
    }

    private function getList(Request $request)
    {
        $params = Business::selectRaw('id, parent_id, value AS field_value')
            ->where('description', 'field')
            ->where(
                function ($query) use ($request) {
                    return $request->searchField ? 
                        $query->where('business.value', 'ilike', $request->searchField . '%') : '';
                }
            )
            ->paginate($request->limit);
        foreach ($params as $key=>$param) {
            $parent = Business::find($param->parent_id);
            if ($parent){
                $param->{'sector_value'} = $parent->value;
                $parent2 = Business::find($parent->parent_id);
                if ($parent2){
                    $param->{'kbli_code_value'} = $parent2->value;
                }
            }
            if ($request->searchKbliCode) {
                // TODO: filter by KBLI code
            }
        }
        return $params;
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
     * @param  \App\Entity\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        return $business;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Business $business)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        DB::beginTransaction();
        try {
            $business->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $business,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
