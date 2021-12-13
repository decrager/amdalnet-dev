<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebgisController extends Controller
{
    public function index()
    {
        $getData = DB::table('project_map_attachments')
            ->select('project_map_attachments.id_project', 'projects.project_title')
            ->leftJoin('projects', 'projects.id', '=', 'project_map_attachments.id_project')
            ->groupBy('project_map_attachments.id_project', 'projects.project_title')
            ->get();

        return response()->json($getData);
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
