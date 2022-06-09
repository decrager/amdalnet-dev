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
                return response()->json(['error' => 'kbli '.$request->sectorsByKbli.' Tidak Ditemukan'], 404);
            }
          } else if ($request->fieldBySector) {
                return BusinessResource::collection(Business::where('description', 'field')->where('parent_id', $request->fieldBySector)->get(['id','value']));
          } else if ($request->fieldBySectorName) {
            return BusinessResource::collection(Business::leftJoin('business as sectors', 'sectors.id','=','business.parent_id')->distinct()
            ->select('business.value')->where('sectors.description','sector')->where('sectors.value', $request->fieldBySectorName)
            ->get());
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
                        $query->where('business.value', 'ilike', '%' . $request->searchField . '%') : '';
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
        $business = $request->business;
        $parent_id = 0;
        if ($business['description'] == 'field') {
            // find available sector id
            $kbli = Business::where('description', 'kbli_code')
                ->where('value', $business['kbli_code'])
                ->first();
            if ($kbli) {
               $sector = Business::where('description', 'sector')
                ->where('value', $business['sector'])
                ->where('parent_id', $kbli->id)
                ->first();
               if ($sector) {
                   $parent_id = $sector->id;
               }
            }
        }
        DB::beginTransaction();
        $created = Business::create([
            'parent_id' => $parent_id,
            'value' => $business['value'],
            'description' => $business['description'],
        ]);
        if ($created) {
            DB::commit();
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $created,
            ], 200);
        } else {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                'errors' => 'Failed to create Business',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        if ($business->description == 'field') {
            // get kbli_code & sector name
            $sector = Business::find($business->parent_id);
            if ($sector) {
                $business->{"sector"} = $sector->value;
                $kbli_code = Business::find($sector->parent_id);
                if ($kbli_code) {
                    $business->{"kbli_code"} = $kbli_code->value;
                }
            }
        }
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
        $updatedBusiness = $request->business;
        if ($business['description'] == 'field') {
            $kbli = null;
            $sector = Business::find($business->parent_id);
            if ($sector) {
                $kbli = Business::find($sector->parent_id);
            }
            if ($updatedBusiness['kbli_code'] != $kbli->value
                || $updatedBusiness['sector'] != $sector->value) {
                // find new sector id
                $newKbli = Business::where('description', 'kbli_code')
                    ->where('value', $updatedBusiness['kbli_code'])
                    ->first();
                if ($newKbli) {
                    $newSector = Business::where('parent_id', $newKbli->id)
                        ->where('value', $updatedBusiness['sector'])
                        ->first();
                    if ($newSector) {
                        $business->parent_id = $newSector->id;
                    }
                }
            }
        }
        $business->value = $updatedBusiness['value'];
        DB::beginTransaction();
        try {
            $business->save();
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
                // 'errors' => 'Failed to update Business',
                'errors' => $e->getMessage(),
            ], 500);
        }
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
