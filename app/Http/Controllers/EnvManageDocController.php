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
        if($request->sppl) {
            $this->uploadSingleFile($request->sppl, 'SPPL', $request->idProject);
        }

        if($request->dpt) {
            $this->uploadSingleFile($request->dpt, 'DPT', $request->idProject);
        }

        if($request->ktr) {
            $this->uploadSingleFile($request->ktr, 'KTR', $request->idProject);
        }

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

        return response()->json(['message' => 'success']);
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

    private function uploadSingleFile($file_64, $type, $id_project)
    {
        $attachment = EnvManageDoc::where([['id_project', $id_project],['type', $type]])->first();
        if(!$attachment) {
            $attachment = new EnvManageDoc();
            $attachment->id_project = $id_project;
            $attachment->type = $type;
        } else {
            Storage::disk('public')->delete($attachment->getRawFilePath());
        }

        $file = $this->base64ToFile($file_64);
        $fileName = strtolower($type) . '/' . uniqid() . '.' . $file['extension'];
        Storage::disk('public')->put($fileName, $file['file']);
        $attachment->filepath = $fileName;
        $attachment->save();
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
