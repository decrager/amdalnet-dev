<?php

namespace App\Http\Controllers;

use App\Entity\Business;
use App\Entity\Kbli;
use App\Entity\OssNib;
use App\Entity\Region;
use App\Entity\SubProject;
use Illuminate\Http\Request;
use stdClass;

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

            if(!$ossnib){
                return new stdClass();
            }

            $dataChecklists = $ossnib->json_content['data_checklist'];
            $dataJsonContent = $ossnib->json_content;

            foreach ($dataJsonContent['data_proyek'] as $key => &$dataProyek) {
                $kbli = Business::where('value', $dataProyek['kbli'])->first();

                if(!$kbli){
                    $dataProyek['nonKbli'] = 'Y';
                }

                foreach ($dataChecklists as $dataChecklist) {
                    if ($dataProyek['id_proyek'] === $dataChecklist['id_proyek']) {
                        $dataProyek['file_izin'] = $dataChecklist['file_izin'];
                        $dataProyek['id_izin'] = $dataChecklist['id_izin'];
                        $dataProyek['kewenangan'] = $dataChecklist['kewenangan'];
                    }
                }

                foreach($dataProyek['data_lokasi_proyek'] as $key => &$dataLokasiProyek){
                    $region = Region::where('region_id', $dataLokasiProyek['proyek_daerah_id'])->first();
                    if($region){
                        $dataLokasiProyek['province'] = $region['province'];
                        $dataLokasiProyek['regency'] = $region['regency'];
                    }
                }

                $isExistSubProject = SubProject::where('id_proyek', $dataProyek['id_proyek'])->first();

                if($isExistSubProject){
                    $dataProyek['status_tapis'] = 'Y';
                }
            }
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
