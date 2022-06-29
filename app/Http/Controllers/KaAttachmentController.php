<?php

namespace App\Http\Controllers;

use App\Entity\AndalAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KaAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return AndalAttachment::where([['id_project', $request->idProject],['is_andal', false]])->get();
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
        $files = json_decode($request->ka_files, true);

        if(count($files) > 0) {
            for($i = 0; $i < count($files); $i++) {
                $fileRequest = 'file-' . $i;

                if($request->input($fileRequest)) {
                    $attachment = new AndalAttachment();
                    $attachment->id_project = $request->idProject;
                    $attachment->name = $files[$i];
                    $attachment->is_pertek = false;
                    $attachment->is_andal = false;

                    $file = $this->base64ToFile($request->input($fileRequest));
                    $fileName = 'project/ka-attachment/' . uniqid() . '.' . $file['extension'];
                    Storage::disk('public')->put($fileName, $file['file']);
                    $attachment->file = $fileName;

                    $attachment->save();
                }
            }
        }

        $deleted = json_decode($request->deleted, true);

        if(count($deleted) > 0) {
            $files = AndalAttachment::whereIn('id', $deleted)->get();
            foreach($files as $file) {
                $file_name = str_replace(Storage::url(''), '', $file->file);
                Storage::disk('public')->delete($file_name);
            }

            AndalAttachment::whereIn('id', $deleted)->delete();
        }

        return response()->json(['message' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
