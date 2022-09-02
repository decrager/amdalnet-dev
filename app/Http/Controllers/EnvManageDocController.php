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
                            $row['filename'] = $this->getFileName($row->getRawFilePath());
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
                    $row['filename'] = $this->getFileName($row->getRawFilePath());
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
            $temp = explode('/', $filepath);
            // $len = count($exp);
            $exp = explode('?', $temp[count($temp) - 1]);
            return $exp[0];
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
        if($request->otherAttachments) {
            $others = json_decode($request->others, true);

            if(count($others) > 0) {
                for($i = 0; $i < count($others); $i++) {
                    $fileRequest = 'file-' . $i;

                    if($request->input($fileRequest)) {
                        $attachment = new EnvManageDoc();
                        $attachment->id_project = $request->idProject;
                        $attachment->name = $others[$i];
                        $attachment->type = 'OTHERS';
    
                        $file = $this->base64ToFile($request->input($fileRequest));
                        $fileName = 'uklupl-attachment/others/' . uniqid() . '.' . $file['extension'];
                        Storage::disk('public')->put($fileName, $file['file']);
                        $attachment->filepath = $fileName;
                        $attachment->save();
                    }
                }
            }

            $deleted = json_decode($request->deleted, true);

            if(count($deleted) > 0) {
                $files = EnvManageDoc::whereIn('id', $deleted)->get();
                foreach($files as $file) {
                    Storage::disk('public')->delete($file->getRawFilePath());
                }

                EnvManageDoc::whereIn('id', $deleted)->delete();
            }

            return EnvManageDoc::where([['id_project', $request->idProject],['type', 'OTHERS']])->get();
        }

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
        } else if ($request->hasFile('ktr')) {
            //create file
            $file = $request->file('ktr');
            $unique_name = uniqid() . '.' . $file->extension();
            $name = 'ktr/' . $unique_name;
            $type = 'KTR';
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

    private function base64ToFile($file_64)
    {
        $extension = explode('/', explode(':', substr($file_64, 0, strpos($file_64, ';')))[1])[1];   // .jpg .png .pdf
      
        $replace = substr($file_64, 0, strpos($file_64, ',')+1); 
      
        // find substring fro replace here eg: data:image/png;base64,
      
        $file = str_replace($replace, '', $file_64); 
      
        $file = str_replace(' ', '+', $file); 
      
        return [
            'extension' => $extension,
            'file' => base64_decode($file)
        ];
    }
}
