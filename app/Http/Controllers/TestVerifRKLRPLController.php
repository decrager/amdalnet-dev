<?php

namespace App\Http\Controllers;

use App\Entity\Announcement;
use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\FormulatorTeam;
use App\Entity\KaForm;
use App\Entity\Lpjp;
use App\Entity\Project;
use App\Entity\ProjectKaForm;
use App\Entity\ProjectMapAttachment;
use App\Entity\PublicConsultation;
use App\Entity\TestingMeeting;
use App\Entity\TestingVerification;
use App\Utils\Html;
use App\Utils\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TestVerifRKLRPLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->checkComplete) {
            $document_type = $request->uklUpl ? 'ukl-upl' : 'rkl-rpl';
            $verification = TestingVerification::where([['id_project', $request->idProject],['document_type', $document_type]])->first();
            if($verification) {
                if($verification->is_complete == null) {
                    return 'false';
                } else if($verification->is_complete == false) {
                    return 'false';
                } else if($verification->is_complete == true) {
                    return 'true';
                }
            } else {
                return 'false';
            }
        }

        if($request->exportNoDocx) {
            $document_type = $request->uklUpl ? 'ukl-upl' : 'rkl-rpl';
            return $this->exportNoDocx($request->idProject, $document_type);
        }

        if($request->project) {
            return Project::whereHas('impactIdentifications')->get();
        }

        if($request->idProject) {
            // Check if verification exist
            $document_type = $request->uklUpl ? 'ukl-upl' : 'rkl-rpl';
            $verifications = TestingVerification::where([['id_project', $request->idProject], ['document_type', $document_type]])->first();
            return $this->getVerification($verifications, $request->idProject, $document_type);
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
        $data = $request->verifications;

        $verification = null;
        $document_type = $request->uklUpl ? 'ukl-upl' : 'rkl-rpl';

        // Save verifications
        if($data['type'] == 'new') {
            $verification = new TestingVerification();
            $verification->id_project = $request->idProject;
            $verification->document_type = $document_type;
        } else {
            $verification = TestingVerification::where([['id_project', $request->idProject],['document_type', $document_type]])->first();
        }
        
        $verification->notes = $data['notes'];

        if($request->complete) {
            $verification->is_complete = $request->decision == 'ya' ? true : false;
        } else {
            $verification->is_complete = $data['is_complete'];
        }
        $verification->save();

        // Save Verifications form
        for($i = 0; $i < count($data['ka_forms']); $i++) {
            $form = null;

            if($data['type'] == 'new') {
                $form = new ProjectKaForm();
                $form->id_testing_verification = $verification->id;
            } else {
                $form = ProjectKaForm::findOrFail($data['ka_forms'][$i]['id']);
            }

            $form->suitability = isset($data['ka_forms'][$i]) ? $data['ka_forms'][$i]['suitability'] : null;
            $form->description = isset($data['ka_forms'][$i]) ? $data['ka_forms'][$i]['description'] : null;
            $form->name = isset($data['ka_forms'][$i]) ? $data['ka_forms'][$i]['name'] : null;
            $form->save();
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

    private function getVerification($verification, $id_project, $document_type) {
        $project = Project::findOrFail($id_project);
        $formulator_team = FormulatorTeam::where('id_project', $id_project)->with(['member' => function($q) {
            $q->orderBy('position', 'desc');
            $q->with('formulator');
            $q->with('expert');
        }])->get();

        $peta_tapak = null;
        $peta_ekologis = null;
        $peta_sosial = null;
        $peta_wilayah_studi = null;
        $peta_tapak_pdf = null;
        $peta_ekologis_pdf = null;
        $peta_sosial_pdf = null;
        $peta_wilayah_studi_pdf = null;
        $maps = ProjectMapAttachment::where('id_project', $id_project)->get();
        foreach($maps as $m) {
            if($m->attachment_type == 'tapak') {
                if($m->file_type == 'SHP') {
                    $peta_tapak = Storage::url('/map' . '/' . $m->stored_filename);
                } else if($m->file_type == 'PDF') {
                    $peta_tapak_pdf = Storage::url('/map' . '/' . $m->stored_filename);
                }
            } else if($m->attachment_type == 'ecology') {
                if($m->file_type == 'SHP') {
                    $peta_ekologis = Storage::url('/map' . '/' . $m->stored_filename);
                } else if($m->file_type == 'PDF') {
                    $peta_ekologis_pdf = Storage::url('/map' . '/' . $m->stored_filename);
                }
            } else if($m->attachment_type == 'social') {
                if($m->file_type == 'SHP') {
                    $peta_sosial = Storage::url('/map' . '/' . $m->stored_filename);
                } else if($m->file_type == 'PDF') {
                    $peta_sosial_pdf = Storage::url('/map' . '/' . $m->stored_filename);
                }
            } else if($m->attachment_type == 'study') {
                if($m->file_type == 'SHP') {
                    $peta_wilayah_studi = Storage::url('/map' . '/' . $m->stored_filename);
                } else if($m->file_type == 'PDF') {
                    $peta_wilayah_studi_pdf = Storage::url('/map' . '/' . $m->stored_filename);
                }
            }
        }
        
        $announcement = Announcement::where('project_id', $id_project)->first();

        // LPJP
        $lpjp = null;
        if($project->type_formulator_team == 'lpjp') {
            $lpjp_data = Lpjp::find($project->id_lpjp);
            if($lpjp_data) {
                $lpjp = [
                    'name' => $lpjp_data->name,
                    'reg_no' => $lpjp_data->reg_no,
                    'cert_file' => $lpjp_data->cert_file
                ];
            }
        }
        // Tim Penyusun Mandiri
        $penyusun_mandiri = null;
        if($project->type_formulator_team == 'mandiri') {
            $mandiri_data = FormulatorTeam::where('id_project', $project->id)->first();
            if($mandiri_data) {
                $penyusun_mandiri = [
                    'name' => $mandiri_data->name
                ];
            }
        }

        // Konsultasi Publik
        $public_consultation = PublicConsultation::where('project_id', $project->id)->with('docs')->first();
        
        // Verification Form Disable
        $is_disabled = false;
        
        $form = [];
        
        if($verification) {
            // Verification Form Disable
            if($verification->is_complete !== null) {
                if($verification->is_complete === false && $verification->notes !== null) {
                    $is_disabled = true;
                } else if($verification->is_complete === true) {
                    $invitation = TestingMeeting::where([['id_project', $id_project],['document_type', $document_type]])->first();
                    if($invitation) {
                        $is_disabled = true;
                    }
                }
            }

            if($verification->forms) {
                if($verification->forms->first()) {
                    foreach($verification->forms as $f) {
                        $type = $f->name == 'tata_ruang' || $f->name == 'persetujuan_awal' ? 'download' : 'non-download';
                        $link = null;
                        if($f->name == 'tata_ruang') {
                            $link = $project->ktr;
                        } else if($f->name == 'persetujuan_awal') {
                            $link = $project->pre_agreement_file;
                        } else if($f->name == 'peta') {
                            $link = $this->petaLink(
                                $peta_tapak, 
                                $peta_sosial, 
                                $peta_ekologis, 
                                $peta_wilayah_studi,
                                $peta_tapak_pdf,
                                $peta_sosial_pdf,
                                $peta_ekologis_pdf,
                                $peta_wilayah_studi_pdf);
                        }


                        $form[] = [
                            'id' => $f->id,
                            'name' => $f->name,
                            'suitability' => $f->suitability,
                            'description' => $f->description,
                            'type' => $type,
                            'link' => $link
                        ];
                    }
                }
            }
        } else {
            $form = [
                [
                  'name' => 'tata_ruang',
                  'link' => $project->ktr,
                  'suitability' => null,
                  'description' => null,
                  'type' => 'download'
                ],
                [
                    'name' => 'persetujuan_awal',
                    'link' => $project->pre_agreement_file,
                    'suitability' => null,
                    'description' => null,
                    'type' => 'download'
                  ],
                  [
                    'name' => 'surat_penyusun',
                    'suitability' => null,
                    'description' => null,
                    'type' => 'non-download'
                  ],
                  [
                    'name' => 'sertifikasi_penyusun',
                    'suitability' => null,
                    'description' => null,
                    'type' => 'non-download'
                  ],
                  [
                    'name' => 'peta',
                    'link' => $this->petaLink(
                        $peta_tapak, 
                        $peta_sosial, 
                        $peta_ekologis, 
                        $peta_wilayah_studi,
                        $peta_tapak_pdf,
                        $peta_sosial_pdf,
                        $peta_ekologis_pdf,
                        $peta_wilayah_studi_pdf),
                    'suitability' => null,
                    'description' => null,
                    'type' => 'non-download'
                  ],
                  [
                    'name' => 'konsul_publik',
                    'suitability' => null,
                    'description' => null,
                    'type' => 'non-download'
                  ],
                  [
                    'name' => 'cv_penyusun',
                    'suitability' => null,
                    'description' => null,
                    'type' => 'non-download'
                  ],
                  [
                    'name' => 'sistematika_penyusunan',
                    'suitability' => 'Sesuai',
                    'description' => null,
                    'type' => 'non-download'
                  ],
                  [
                    'name' => 'pertek',
                    'suitability' => null,
                    'description' => null,
                    'type' => 'non-download'
                  ],
                  [
                    'name' => 'peta_titik',
                    'suitability' => null,
                    'description' => null,
                    'type' => 'non-download'
                  ],
            ];
        }


        $data = [
            'type' => $verification ? 'update' : 'new',
            'id_project' => $id_project,
            'ka_forms' => $form,
            'formulator_team' => $formulator_team,
            'announcement' => $announcement,
            'public_consultation' => $public_consultation,
            'notes' => $verification ? $verification->notes : null,
            'old_notes' => $verification ? $verification->notes : null,
            'is_complete' => $verification ? $verification->is_complete : null,
            'project' => $project,
            'lpjp' => $lpjp,
            'penyusun_mandiri' => $penyusun_mandiri,
            'is_disabled' => $is_disabled
        ];

        return $data;
    }

    private function petaLink(
        $peta_tapak, 
        $peta_sosial, 
        $peta_ekologis, 
        $peta_wilayah_studi,
        $peta_tapak_pdf,
        $peta_sosial_pdf,
        $peta_ekologis_pdf,
        $peta_wilayah_studi_pdf) {
        return [
            [
              'name' => 'Peta Tapak Proyek',
              'link' => $peta_tapak,
              'pdf' => $peta_tapak_pdf
            ],
            [
              'name' => 'Peta Batas Sosial',
              'link' => $peta_sosial,
              'pdf' => $peta_sosial_pdf
            ],
            [
              'name' => 'Peta Batas Ekologis',
              'link' => $peta_ekologis,
              'pdf' => $peta_ekologis_pdf
            ],
            [
              'name' => 'Peta Batas Wilayah Studi',
              'link' => $peta_wilayah_studi,
              'pdf' => $peta_wilayah_studi_pdf
            ],
            [
                'name' => 'Webgis'
            ]
        ];
    }

    private function exportNoDocx($id_project, $document_type)
    {
        if (!File::exists(storage_path('app/public/adm-no/'))) {
            File::makeDirectory(storage_path('app/public/adm-no/'));
        }

        $project = Project::findOrFail($id_project);
        $verification = TestingVerification::where([['id_project', $id_project],['document_type', $document_type]])->first();
        
        Carbon::setLocale('id');

        $docs_date = Carbon::createFromFormat('Y-m-d H:i:s', $verification->updated_at)->isoFormat('D MMMM Y');

        $project_address = '';
        if($project->address) {
            if($project->address->first()) {
                $project_address = $project->address->first()->address . ' ' . ucwords(strtolower($project->address->first()->district)) . ' Provinsi ' . ucwords(strtolower($project->address->first()->prov));
            }
        }

         // TUK PUSAT    
        $ketua_tuk_name = '';
        $ketua_tuk_nip = '';

        $tuk_pusat = FeasibilityTestTeam::where('authority', 'Pusat')->first();
        if($tuk_pusat) {
            $ketua = FeasibilityTestTeamMember::where([['id_feasibility_test_team', $tuk_pusat->id],['position', 'Ketua']])->first();
            if($ketua) {
                if($ketua->expertBank) {
                    $ketua_tuk_name = $ketua->expertBank->name;
                } else if($ketua->lukMember) {
                    $ketua_tuk_name = $ketua->lukMember->name;
                    $ketua_tuk_nip = $ketua_tuk_name->lukMember->nip ?? '';
                }
            }
        }

        $templateProcessor = new TemplateProcessor('template_berkas_adm_no.docx');
        $templateProcessor->setValue('docs_date', $docs_date);
        $templateProcessor->setValue('pemrakarsa', $project->initiator->name);
        $templateProcessor->setValue('pemrakarsa_address', $project->initiator->address);
        $templateProcessor->setValue('project_title', $project->project_title);
        $templateProcessor->setValue('project_address', $project_address);
        $templateProcessor->setValue('ketua_tuk_name', $ketua_tuk_name);
        $templateProcessor->setValue('ketua_tuk_nip', $ketua_tuk_nip);
        if($document_type == 'rkl-rpl') {
            $templateProcessor->setValue('document_type', 'Andal RKL RPL');
        } else {
            $templateProcessor->setValue('document_type', 'UKL UPL');
        }

        $notesTable = new Table();
        $notesTable->addRow();
        $cell = $notesTable->addCell();
        Html::addHtml($cell, $verification->notes);

        $templateProcessor->setComplexBlock('notes', $notesTable);
        $templateProcessor->saveAs(storage_path('app/public/adm-no/hasil-adm-' . strtolower($project->project_title) . '.docx'));

        return strtolower($project->project_title);
    }
}
