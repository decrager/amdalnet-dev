<?php

namespace App\Http\Controllers;

use App\Entity\MeetingReport;
use App\Entity\Project;
use App\Entity\ProjectSkkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SKKLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->information) {
            return $this->getInformation($request->idProject);
        }

        if($request->document) {
            return $this->getDocument($request->idProject);
        }

        if($request->map) {
            return Project::findOrFail($request->idProject);
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
        $data = $request->all();
        if($request->hasFile('skkl')) {
             //create file
             $file = $request->file('skkl');
             $name = '/skkl/' . uniqid() . '.' . $file->extension();
             $file->storePubliclyAs('public', $name);
 
             //create environmental expert  
            $skkl = ProjectSkkl::where('id_project', $data['idProject'])->first();

            if(!$skkl) {
                $skkl = new ProjectSkkl();
                $skkl->id_project = $data['idProject'];
            }

             $skkl->file = Storage::url($name);
             $skkl->save();

             return response()->json(['message' => 'success']);
        }

        return response()->json(['message' => 'failed'], 404);
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

    private function getInformation($idProject) {
        $data = [];
        $beritaAcara = MeetingReport::where('id_project', $idProject)->first();

        $project = Project::findOrFail($idProject);
        $data[] = [
            'title' => 'Nama Kegiatan',
            'description' => $project->project_title,
        ];
        $data[] = [
            'title' => 'Bidang Usaha/Kegiatan',
            'description' => 'Bidang ' . $project->sector
        ];
        $data[] = [
            'title' => 'Skala/Besaran',
            'description' => $project->scale . ' ' . $project->scale_unit
        ];
        $data[] = [
            'title' => 'Alamat',
            'description' => $project->address
        ];
        $data[] = [
            'title' => 'Pemrakarsa',
            'description' => $project->initiator->name
        ];
        $data[] = [
            'title' => 'Penanggung Jawab',
            'description' => $project->initiator->pic
        ];
        $data[] = [
            'title' => 'Alamat Pemrakarsa',
            'description' => $project->initiator->address
        ];
        $data[] = [
            'title' => 'No Telepon Pemrakarsa',
            'description' => $project->initiator->phone
        ];
        $data[] = [
            'title' => 'Email Pemrakarsa',
            'description' => $project->initiator->email
        ];
        $data[] = [
            'title' => 'Provinsi/Kota',
            'description' => $project->province->name . '/' . $project->district->name
        ];
        $data[] = [
            'title' => 'Deskripsi Kegiatan',
            'description' => null,
            'wider' => true,
            'type' => 'title'
        ];
        $data[] = [
            'title' => $project->description,
            'description' => null,
            'wider' => true,
            'type' => 'description'
        ];
        $data[] = [
            'title' => 'Deskripsi Lokasi',
            'description' => null,
            'wider' => true,
            'type' => 'title'
        ];
        $data[] = [
            'title' => $project->location_desc,
            'description' => null,
            'wider' => true,
            'type' => 'description'
        ];

        return $data;
    }

    private function getDocument($idProject) {
        $skkl = ProjectSkkl::where('id_project', $idProject)->first();

        return [ 
                [
                    'name' => 'Persetujuan Lingkungan SKKL',
                    'file' => $skkl ? $skkl->file : null,
                    'updated_at' => $skkl ? date('d F Y', strtotime($skkl->updated_at)) : '-'
                ],
                [
                    'name' => 'Dokumen KA',
                    'file' => '/ukl-upl/'.$idProject.'/docx',
                    'updated_at' => date('d F Y', strtotime(Project::findOrFail($idProject)->updated_at))
                ],
                [
                    'name' => 'Dokumen ANDAL',
                    'file' => null,
                    'updated_at' => '-'
                ],
                [
                    'name' => 'Dokumen RKL RPL',
                    'file' => null,
                    'updated_at' => '-'
                ]
            ];
    }
}
