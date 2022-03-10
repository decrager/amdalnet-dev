<?php

namespace App\Http\Controllers;

use App\Entity\Business;
use App\Entity\Kbli;
use App\Entity\OssNib;
use Illuminate\Http\Request;

class OssNibController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->nib) {

            $ossnib = OssNib::where('nib', $request->nib)->first();

            $dataChecklists = $ossnib->json_content['data_checklist'];
            $dataJsonContent = $ossnib->json_content;

            // $ossnib->json_content['data_proyek']->map(function ($object) {
            //     $object->hihi = 'a';
            // });

            // $dataJsonContent = array_map(function ($a) {
            //     foreach ($dataChecklists as $dataChecklist) {
            //         if ($a['id_proyek'] === $dataChecklist['id_proyek']) {
            //             $a['file_izin'] = $dataChecklist['file_izin'];
            //         }
            //     }
            //     return $a;
            // }, $dataJsonContent);

            foreach ($dataJsonContent['data_proyek'] as $key => &$dataProyek) {
                $kbli = Business::where('value', $dataProyek['kbli'])->first();

                if(!$kbli){
                    $dataProyek['nonKbli'] = 'Y';
                }

                foreach ($dataChecklists as $dataChecklist) {
                    if ($dataProyek['id_proyek'] === $dataChecklist['id_proyek']) {
                        $dataProyek['file_izin'] = $dataChecklist['file_izin'];
                        // $ossnib->json_content['data_proyek'][$key]['file_izin'] = $dataChecklist['file_izin'];
                    }
                }
            }

            //filter
            // $dataJsonContent['data_proyek'] = array_filter($dataJsonContent['data_proyek'], function($value) {
            //     return $value['file_izin'] !== '-';
            // });
            

            // return $dataChecklist;
            return $dataJsonContent;
        }
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
     * @param  \App\Entity\OssNib  $ossNib
     * @return \Illuminate\Http\Response
     */
    public function show(OssNib $ossNib)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\OssNib  $ossNib
     * @return \Illuminate\Http\Response
     */
    public function edit(OssNib $ossNib)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\OssNib  $ossNib
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OssNib $ossNib)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\OssNib  $ossNib
     * @return \Illuminate\Http\Response
     */
    public function destroy(OssNib $ossNib)
    {
        //
    }
}
