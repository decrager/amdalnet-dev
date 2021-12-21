<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PolicyResource;
use App\Entity\Policy;

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

        $regulations = Policy::When($request->has('keyword'), function ($query) use ($request) {
            $columnsToSearch = ['about', 'set'];
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
        ->orderby('created_at', $sort ?? 'DESC')->paginate($request->limit ? $request->limit : 10);

        return response()->json($regulations, 200);
    }
}
