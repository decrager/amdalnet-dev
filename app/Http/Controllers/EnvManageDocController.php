<?php

namespace App\Http\Controllers;

use App\Entity\EnvManageDoc;
use App\Http\Resources\EnvManageDocResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EnvManageDocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if (isset($params['id_project'])) {
            if (isset($params['type'])) {
                $rows = EnvManageDoc::select('*')
                    ->where('id_project', $params['id_project'])
                    ->where('type', $params['type'])
                    ->orderBy('updated_at', 'desc')
                    ->get();
                    if (count($rows) > 0) {
                        foreach ($rows as $row) {
                            $row['filename'] = $this->getFileName($row->filepath);
                        }
                }
                return EnvManageDocResource::collection($rows);
            }
            $rows = EnvManageDoc::select('*')
                ->where('id_project', $params['id_project'])
                ->orderBy('updated_at', 'desc')
                ->get();
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    $row['filename'] = $this->getFileName($row->filepath);
                }
            }
            return EnvManageDocResource::collection($rows);
        }
        return response()->json([
            'status' => 400,
            'code' => 400,
            'message' => 'Bad request',
        ], 400);
    }

    private function getFileName($filepath) {
        if (!empty($filepath)) {
            $exp = explode('/', $filepath);
            $len = count($exp);
            return $exp[$len-1];
        }
        return '';
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
        // upload file
        $params = $request->all();
        $type = '';
        $name = '';
        $unique_name = '';
        if ($request->hasFile('sppl')) {
            //create file
            $file = $request->file('sppl');
            $unique_name = uniqid() . '.' . $file->extension();
            $name = 'sppl/' . $unique_name;
            $type = 'SPPL';
        } else if ($request->hasFile('dpt')) {
            //create file
            $file = $request->file('dpt');
            $unique_name = uniqid() . '.' . $file->extension();
            $name = 'dpt/' . $unique_name;
            $type = 'DPT';
        }
        DB::beginTransaction();
        try {
            $file->storePubliclyAs('public', $name);
            $envManageDoc = null;
            if ($params['is_update']) {
                $envManageDoc = EnvManageDoc::find($params['id']);
                if ($envManageDoc != null) {
                    $envManageDoc->filepath = $name;
                    $envManageDoc->save();
                }
            } else {
                $envManageDoc = EnvManageDoc::create([
                    'id_project' => $params['id_project'],
                    'type' => $type,
                    'filepath' => $name,
                ]);
            }
            DB::commit();
            $envManageDoc['filename'] = $unique_name;
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $envManageDoc,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\EnvManageDoc  $envManageDoc
     * @return \Illuminate\Http\Response
     */
    public function show(EnvManageDoc $envManageDoc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\EnvManageDoc  $envManageDoc
     * @return \Illuminate\Http\Response
     */
    public function edit(EnvManageDoc $envManageDoc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\EnvManageDoc  $envManageDoc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnvManageDoc $envManageDoc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\EnvManageDoc  $envManageDoc
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnvManageDoc $envManageDoc)
    {
        //
    }
}
