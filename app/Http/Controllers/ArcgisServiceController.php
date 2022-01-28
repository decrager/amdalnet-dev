<?php

namespace App\Http\Controllers;

use App\Entity\ArcgisService;
use App\Entity\ArcgisServiceCategory;
use Illuminate\Http\Request;

class ArcgisServiceController extends Controller
{
    public function arcgisServiceList(Request $request)
    {
        $getArcgisService = ArcgisService::with('category')
            ->when($request->has('is_proxy'), function ($query) use ($request) {
                return $query->where('is_proxy', '=', $request->is_proxy);
            })
            ->when($request->has('id_province'), function ($query) use ($request) {
                return $query->where('id_province', '=', $request->id_province);
            })
            ->paginate(10);

        return response()->json($getArcgisService);
    }

    public function arcgisServiceCategoryList()
    {
        $getArcgisServiceCategory = ArcgisServiceCategory::get();

        return response()->json($getArcgisServiceCategory);
    }

    public function showArcgisServiceList($id)
    {
        $getData = ArcgisService::where('id', '=', $id)->first();

        if (!$getData) {
            return response()->json([
                'message' => 'No Data Available'
            ]);
        }

        return $getData;
    }

    public function showArcgisServiceCategoryList($id)
    {
        $getData = ArcgisServiceCategory::where('id', '=', $id)->first();

        if (!$getData) {
            return response()->json([
                'message' => 'No Data Available'
            ]);
        }

        return $getData;
    }

    public function createArcgisServiceCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        $checkCategoryName = ArcgisServiceCategory::where('category_name', '=', $request->category_name)->first();

        if ($checkCategoryName) {
            return response()->json([
                'message' => 'Category Already Exists'
            ]);
        }

        ArcgisServiceCategory::create($request->all());

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function createArcgisService(Request $request)
    {
        $request->validate([
            'id_category' => 'required',
            'url_service' => 'required',
            'source' => 'required',
            'organization' => 'required',
            'name' => 'required',
        ]);

        $checkUrl = ArcgisService::where('url_service', '=', $request->url_service)->first();

        if ($checkUrl) {
            return response()->json([
                'message' => 'Url Already Exists'
            ]);
        }

        ArcgisService::create($request->all());

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function updateArcgisService(Request $request, $id)
    {
        $request->validate([
            'id_category' => 'required',
            'url_service' => 'required',
            'source' => 'required',
            'organization' => 'required',
            'name' => 'required',
        ]);

        try {
            ArcgisService::where('url_service', '=', $request->url_service)->first();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Url Already Exists'
            ]);
        }

        try {
            ArcgisService::find($id)->update($request->all());
            return response()->json([
                'message' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal Update'
            ]);
        }
    }

    public function deleteAcrgisService($id)
    {
        try {
            $data = ArcgisService::find($id);
            $data->delete();
            return response()->json([
                'message' => 'Sukses'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal Hapus'
            ]);
        }
    }

    public function deleteAcrgisServiceCategory($id)
    {
        try {
            $data = ArcgisServiceCategory::find($id);
            $data->delete();
            return response()->json([
                'message' => 'Sukses'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal Hapus'
            ]);
        }
    }
}
