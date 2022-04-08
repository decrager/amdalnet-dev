<?php

namespace App\Http\Controllers;

use App\Entity\EnvImpactAnalysis;
use App\Entity\EnvManagePlan;
use App\Entity\EnvMonitorPlan;
use App\Entity\Initiator;
use App\Entity\MeetingReport;
use App\Entity\OssNib;
use App\Entity\Project;
use App\Entity\ProjectSkkl;
use App\Services\OssService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;

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
            if($request->uklUpl) {
                return $this->getDocumentUklUpl($request->idProject);
            }

            return $this->getDocument($request->idProject);
        }

        if($request->map) {
            return Project::findOrFail($request->idProject);
        }

        if ($request->skkl) {
            return $this->getDetailSkkl($request->idProject);
        }

        if ($request->skklOss) {
            return $this->getSkklOssFile($request->idProject);
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
            $project = Project::findOrFail($data['idProject']);
            $file_name = $project->project_title .' - ' . $project->initiator->name;
            

             //create file
             $file = $request->file('skkl');
             $name = '/skkl/' . $file_name . '.' . $file->extension();
             $file->storePubliclyAs('public', $name);
 
             //create environmental expert  
            $skkl = ProjectSkkl::where('id_project', $data['idProject'])->first();

            if(!$skkl) {
                $skkl = new ProjectSkkl();
                $skkl->id_project = $data['idProject'];
            }

             $skkl->file = Storage::url($name);
             $skkl->save();

             // send status 45 to OSS
             OssService::receiveLicenseStatus($project, '45');

              // === WORKFLOW === //
            if($project->marking == 'amdal.recommendation-signed') {
                $project->workflow_apply('publish-amdal-skkl');
                $project->save();
            } else if($project->marking == 'uklupl-mt.recommendation-signed') {
                $project->workflow_apply('publish-uklupl-pkplh');
                $project->save();
            }

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

        // ========= PROJECT ADDRESS =========== //
        $district = '';
        $province = '';
        $address = '';
        if($project->address && $project->address->first()) {
            $district = $project->address->first()->district;
            $province = $project->address->first()->province;
            $address = $project->address->first()->address;
        }
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
            'description' => $address
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
            'description' => $province . '/' . $district
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
        $project = Project::findOrFail($idProject);

        // === DOKUMEN KA, ANDAL & RKL RPL === //
        // Date
        $andalDate = '';
        $andal = EnvImpactAnalysis::whereHas('impactIdentification', function($q) use($idProject) {
           $q->where('id_project', $idProject); 
        })->orderBy('updated_at', 'desc')->first();
        if($andal) {
            $andalDate = $andal->updated_at->locale('id')->isoFormat('D MMMM Y');
        }

        $rklDate = '';
        $rkl = EnvManagePlan::whereHas('impactIdentification', function($q) use($idProject) {
            $q->where('id_project', $idProject); 
         })->orderBy('updated_at', 'desc')->first();
         if($rkl) {
             $rklDate = $rkl->updated_at;
         }

        $rplDate = '';
        $rpl = EnvMonitorPlan::whereHas('impactIdentification', function($q) use($idProject) {
            $q->where('id_project', $idProject); 
         })->orderBy('updated_at', 'desc')->first();
         if($rpl) {
            $rplDate = $rpl->updated_at;
        }

        $rklRplDate = '';
        if($rklDate == '') {
            $rklRplDate = $rplDate;
        } else if ($rplDate == '') {
            $rklRplDate = $rklDate;
        } else {
            $rklRplDate = $rklDate > $rplDate ? $rklDate->locale('id')->isoFormat('D MMMM Y') : $rplDate->locale('id')->isoFormat('D MMMM Y');
        }

        // === SKKL === //
        $skkl_download_name = null;
        $update_date_skkl = null;
        $skkl = ProjectSkkl::where('id_project', $idProject)->first();
        if($skkl) {
            $skkl_download_name = $skkl->file;
            $update_date_skkl = $skkl->updated_at->locale('id')->isoFormat('D MMMM Y');
        } else {
            // ============== PROJECT ADDRESS =============== //
            $location = '';
            if($project->address) {
                if($project->address->first()) {
                    $district = $project->address->first()->district;
                    $province = $project->address->first()->province;
                    $address = $project->address->first()->address;
                    $location = $address . ' ' . ucwords(strtolower($district)) . ', Provinsi ' . $province;
                }
            }

            // PHPWord
            $templateProcessor = new TemplateProcessor('template_skkl.docx');
            $templateProcessor->setValue('project_title_big', strtoupper($project->project_title));
            $templateProcessor->setValue('pemrakarsa_big', strtoupper($project->initiator->name));
            $templateProcessor->setValue('project_title', $project->project_title);
            $templateProcessor->setValue('pemrakarsa', $project->initiator->name);
            $templateProcessor->setValue('project_type', $project->project_type);
            $templateProcessor->setValue('pic', $project->initiator->name);
            $templateProcessor->setValue('pic_position', $project->initiator->pic_role);
            $templateProcessor->setValue('pemrakarsa_address', $project->initiator->address);
            $templateProcessor->setValue('location', $location);

            $save_file_name = str_replace('/', '-', $project->project_title) .' - ' . $project->initiator->name . '.docx';
            if (!File::exists(storage_path('app/public/skkl/'))) {
                File::makeDirectory(storage_path('app/public/skkl/'));
            }

            $templateProcessor->saveAs(storage_path('app/public/skkl/' . $save_file_name));
            $skkl_download_name = Storage::url('skkl/' . $save_file_name);
            $update_date_skkl = $project->updated_at->locale('id')->isoFormat('D MMMM Y');
        }
 

        return [ 
                [
                    'name' => 'Persetujuan Lingkungan SKKL',
                    'file' => $skkl_download_name,
                    'updated_at' => $update_date_skkl,
                    'uploaded' => $skkl
                ],
                [
                    'name' => 'Dokumen KA',
                    'file' => Storage::url('formulir/' . $idProject . '-form-ka-andal.docx'),
                    'updated_at' => $project->updated_at->locale('id')->isoFormat('D MMMM Y')
                ],
                [
                    'name' => 'Dokumen ANDAL',
                    'file' =>  Storage::url('workspace/' . $idProject . '-andal.docx'),
                    'updated_at' => $andalDate
                ],
                [
                    'name' => 'Dokumen RKL RPL',
                    'file' => Storage::url('workspace/' .  $idProject . '-rkl-rpl.docx'),
                    'updated_at' => $rklRplDate
                ]
            ];
    }

    private function getDocumentUklUpl($idProject) {
        $project = Project::findOrFail($idProject);

        // ============== PROJECT ADDRESS =============== //
        $location = '';
        if($project->address) {
            if($project->address->first()) {
                $district = $project->address->first()->district;
                $province = $project->address->first()->province;
                $address = $project->address->first()->address;
                $location = $address . ' ' . ucwords(strtolower($district)) . ', Provinsi ' . $province;
            }
        }

        // PHPWord
        $templateProcessor = new TemplateProcessor('template_pkplh.docx');
        $templateProcessor->setValue('project_title_big', strtoupper($project->project_title));
        $templateProcessor->setValue('pemrakarsa_big', strtoupper($project->initiator->name));
        $templateProcessor->setValue('project_title', $project->project_title);
        $templateProcessor->setValue('pemrakarsa', $project->initiator->name);
        $templateProcessor->setValue('pemrakarsa_nib', $project->initiator->nib);
        $templateProcessor->setValue('project_type', $project->project_type);
        $templateProcessor->setValue('pemrakarsa_pic', $project->initiator->name);
        $templateProcessor->setValue('pemrakarsa_position', $project->initiator->pic_role);
        $templateProcessor->setValue('pemrakarsa_address', $project->initiator->address);
        $templateProcessor->setValue('project_address', $location);

        $save_file_name = str_replace('/', '-', $project->project_title) .' - ' . $project->initiator->name . '.docx';
        if (!File::exists(storage_path('app/public/pkplh/'))) {
            File::makeDirectory(storage_path('app/public/pkplh/'));
        }

        $templateProcessor->saveAs(storage_path('app/public/pkplh/' . $save_file_name));

        $uklDate = '';
        $ukl = EnvManagePlan::whereHas('impactIdentification', function($q) use($idProject) {
            $q->where('id_project', $idProject); 
         })->orderBy('updated_at', 'desc')->first();
         if($ukl) {
             $uklDate = $ukl->updated_at;
         }

        $uplDate = '';
        $upl = EnvMonitorPlan::whereHas('impactIdentification', function($q) use($idProject) {
            $q->where('id_project', $idProject); 
         })->orderBy('updated_at', 'desc')->first();
         if($upl) {
            $uplDate = $upl->updated_at;
        }

        $uklUplDate = '';
        if($uklDate == '') {
            $uklUplDate = $uplDate;
        } else if ($uplDate == '') {
            $uklUplDate = $uklDate;
        } else {
            $uklUplDate = $uklDate > $uplDate ? $uklDate->locale('id')->isoFormat('D MMMM Y') : $uplDate->locale('id')->isoFormat('D MMMM Y');
        }

        // === WORKFLOW === //
        // if($project->marking == 'uklupl-mt.recommendation-signed') {
        //     $project->workflow_apply('publish-uklupl-pkplh');
        //     $project->save();
        // }
 

        return [ 
                [
                    'name' => 'PKPLH',
                    'file' => Storage::url('pkplh/' . $save_file_name),
                    'updated_at' => $project->updated_at->locale('id')->isoFormat('D MMMM Y')
                ],
                [
                    'name' => 'Dokumen UKL UPL',
                    'file' => Storage::url('workspace/ukl-upl-' . strtolower($project->project_title) . '.docx'),
                    'updated_at' => $uklUplDate
                ]
            ];
    }

    private function getDetailSkkl($idProject)
    {
        $skkl = ProjectSkkl::where('id_project', $idProject)->first();

        if (!$skkl) {
            Log::error('SKKL with id_project ' . $idProject . ' not found.');
            return false;
        }

        return $skkl;
    }

    private function getDataNib($idProject)
    {
        $project = Project::findOrFail($idProject);
        $initiator = Initiator::find($project->id_applicant);
        if (!$initiator) {
            Log::error('Initiator not found');
            return false;
        }
        $ossNib = OssNib::where('nib', $initiator->nib)->first();
        if (!$ossNib) {
            Log::error('OSSNib not found');
            return false;
        }
        $jsonContent = $ossNib->json_content;
        return [
            'nib' => isset($jsonContent['nib']) ? $jsonContent['nib'] : null,
            'id_izin' => isset($jsonContent['id_izin']) ? $jsonContent['id_izin'] : null,
        ];
    }

    private function getSkklOssFile($idProject)
    {
        $dataNib = $this->getDataNib($idProject);
        $fileUrl = null;
        try {
            $resp = OssService::inqueryFileDS($dataNib['nib'], $dataNib['id_izin']);
            $fileUrl = $resp['responInqueryFileDS']['url_file_ds'];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return [
            'file_url' => $fileUrl,
            'user_key' => env('OSS_USER_KEY'),
        ];
    }
}
