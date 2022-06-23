<?php

namespace App\Http\Controllers;

use App\Entity\Announcement;
use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\Feedback;
use App\Entity\FormulatorTeam;
use App\Entity\FormulatorTeamMember;
use App\Entity\KaForm;
use App\Entity\Lpjp;
use App\Entity\Project;
use App\Entity\ProjectKaForm;
use App\Entity\ProjectMapAttachment;
use App\Entity\PublicConsultation;
use App\Entity\TestingMeeting;
use App\Entity\TestingVerification;
use App\Entity\TukProject;
use App\Laravue\Models\User;
use App\Notifications\TestingVerificationNotification;
use App\Utils\Html;
use App\Utils\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use PDF;

class TestingVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->spt) {
            return $this->rekapSPT($request->idProject);
        }

        if($request->checkComplete) {
            $verification = TestingVerification::where([['id_project', $request->idProject],['document_type', 'ka']])->first();
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
            return $this->exportNoDocx($request->idProject);
        }

        if($request->project) {
            return Project::whereHas('impactIdentifications')->get();
        }

        if($request->idProject) {
            // Check if verification exist
            $verifications = TestingVerification::where([['id_project', $request->idProject], ['document_type', 'ka']])->first();
            return $this->getVerification($verifications, $request->idProject);
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
        $project = Project::findOrFail($request->idProject);

        $verification = null;

        // Save verifications
        if($data['type'] == 'new') {
            $verification = new TestingVerification();
            $verification->id_project = $request->idProject;
            $verification->document_type = 'ka';
        } else {
            $verification = TestingVerification::where([['id_project', $request->idProject],['document_type', 'ka']])->first();
        }

        $verification->notes = $data['notes'];

        if($request->complete) {
            $verification->is_complete = $request->decision == 'ya' ? true : false;

            // === WORKFLOW === //
            if($project->marking == 'amdal.form-ka-adm-review') {
                if($request->decison == 'ya') {
                    $project->workflow_apply('approve-amdal-form-ka');
                } else {
                    $project->workflow_apply('return-amdal-form-ka-review');
                }
            }

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

        // === NOTIFICATIONS === //
        // A. PEMERIKSAAN
        if($data['type'] == 'new') {
            // 1. Pemrakarsa
            $pemrakarsa_user = User::where('email', $project->initiator->email)->first();
            if($pemrakarsa_user) {
                Notification::send([$pemrakarsa_user], new TestingVerificationNotification($verification, $pemrakarsa_user->name, 'pemrakarsa', 'pemeriksaan'));
            }

            // 2. Penyusun
            $formulator_team_members = FormulatorTeamMember::whereHas('team', function($q) use($project) {
                $q->where('id_project', $project->id);
            })->get();
            foreach($formulator_team_members as $ftm) {
                if($ftm->formulator) {
                    $formulator_user = User::where('email', $ftm->formulator->email)->first();
                    if($formulator_user) {
                        Notification::send([$formulator_user], new TestingVerificationNotification($verification, $formulator_user->name, 'penyusun', 'pemeriksaan'));
                    }
                } else if($ftm->expert) {
                    $expert_user = User::where('email', $ftm->expert->email)->first();
                    if($expert_user) {
                        Notification::send([$expert_user],new TestingVerificationNotification($verification, $expert_user->name, 'penyusun', 'pemeriksaan'));
                    }
                }
            }

            // 3. Kepala Sekretariat TUK
            $authority = strtolower($project->authority) == 'kabupaten' ? 'Kabupaten/Kota' : ucwords(strtolower($project->authority));
            $kepala_sekretariat = FeasibilityTestTeamMember::whereHas('feasibilityTestTeam', function($q) use($project, $authority) {
                $q->where('authority', $authority);
                if($authority == 'Kabupaten/Kota') {
                    $q->where('id_district_name', $project->auth_district);
                } else if($authority == 'Provinsi') {
                    $q->where('id_province_name', $project->auth_province);
                }
            })->where('position', 'Kepala Sekretariat')->first();
            if($kepala_sekretariat) {
                $ks_user = null;
                if($kepala_sekretariat->lukMember) {
                    $ks_user = User::where('email', $kepala_sekretariat->lukMember->email)->first();
                } else if($kepala_sekretariat->expertBank) {
                    $ks_user = User::where('email', $kepala_sekretariat->expertBank->email)->first();
                }

                if($ks_user) {
                    Notification::send([$ks_user],new TestingVerificationNotification($verification, $ks_user->name, 'kepala sekretariat', 'pemeriksaan'));
                }
            }

            // 4.Validator Administasi/PJM
            $tuk_member = TukProject::where('id_project', $project->id)->whereIn('role', ['valadm','pjm'])->get();
            foreach($tuk_member as $tm) {
                $tuk_user = User::find($tm->id_user);
                if($tuk_user) {
                    Notification::send([$tuk_user],new TestingVerificationNotification($verification, $tuk_user->name, 'valadm', 'pemeriksaan'));
                }
            }
        }

        // B. PENILAIAN SELESAI
        if($request->complete) {
             // 1. Pemrakarsa
             $pemrakarsa_user = User::where('email', $project->initiator->email)->first();
             if($pemrakarsa_user) {
                 Notification::send([$pemrakarsa_user], new TestingVerificationNotification($verification, $pemrakarsa_user->name, 'pemrakarsa', 'selesai'));
             }
             // 2. Penyusun Non TA
             $formulator_team_members = FormulatorTeamMember::whereHas('team', function($q) use($project) {
                $q->where('id_project', $project->id);
            })->whereIn('position', ['Ketua', 'Anggota'])->get();
            foreach($formulator_team_members as $ftm) {
                if($ftm->formulator) {
                    $formulator_user = User::where('email', $ftm->formulator->email)->first();
                    if($formulator_user) {
                        Notification::send([$formulator_user], new TestingVerificationNotification($verification, $formulator_user->name, 'penyusun', 'pemeriksaan'));
                    }
                }
            }

        }

        /* WORKFLOW */
        $project = Project::where('id', $verification->id_project)->first();
        if($project && $project->marking == 'amdal.form-ka-submitted'){

            if($verification->is_complete){
                $project->workflow_apply('review-amdal-form-ka');
                $project->workflow_apply('approve-amdal-form-ka');
                $project->save();
            } else {
                $project->workflow_apply('review-amdal-form-ka');
                $project->workflow_apply('return-amdal-form-ka');
                $project->save();
            }
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

    private function getVerification($verification, $id_project) {
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
                    'name' => $mandiri_data->name,
                    'sk_letter' => $mandiri_data->evidence_letter
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
                    $invitation = TestingMeeting::where([['id_project', $id_project],['document_type', 'ka']])->first();
                    if($invitation) {
                        $is_disabled = true;
                    }
                }
            }

            if($verification->forms) {
                if($verification->forms->first()) {
                    foreach($verification->forms as $f) {
                        $type = $f->name == 'tata_ruang' || $f->name == 'persetujuan_awal' || $f->name == 'pippib' ? 'download' : 'non-download';
                        $link = null;
                        if($f->name == 'tata_ruang') {
                            $link = $project->ktr;
                        } else if($f->name == 'pippib') {
                            $link = $project->ppib_file;
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
                  'name' => 'pippib',
                  'link' => $project->ppib_file,
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
            'pre_agreement' => $project->pre_agreement_file,
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
              'name' => 'Kesesuaian Peta dengan Kawasan Lindung',
              'link' => $peta_tapak,
              'pdf' => $peta_tapak_pdf
            ],
            [
                'name' => 'Webgis'
            ]
        ];
    }

    private function rekapSPT($id_project)
    {
        $feedbacks = Feedback::whereHas('announcement', function($q) use($id_project) {
            $q->where('project_id', $id_project);
        })->where('is_relevant', true)->get();

        $pdf = PDF::loadView('document.template_rekap_spt', compact('feedbacks'))->setPaper('a4', 'potrait');
        return $pdf->download('Rekap SPT Masyarakat.pdf');
    }

    private function exportNoDocx($id_project)
    {
        if (!Storage::disk('public')->exists('adm-no')) {
            Storage::disk('public')->makeDirectory('adm-no');
        }

        $project = Project::findOrFail($id_project);
        $verification = TestingVerification::where([['id_project', $id_project],['document_type', 'ka']])->first();

        Carbon::setLocale('id');

        $docs_date = Carbon::createFromFormat('Y-m-d H:i:s', $verification->updated_at)->isoFormat('D MMMM Y');

        $project_address = '';
        if($project->address) {
            if($project->address->first()) {
                $project_address = $project->address->first()->address . ' ' . ucwords(strtolower($project->address->first()->district)) . ' Provinsi ' . ucwords(strtolower($project->address->first()->prov));
            }
        }

        // === TUK === //
        $tuk = null;
        $ketua_tuk_name = '';
        $ketua_tuk_nip = '';
        $authority = '';
        $authority_big = '';
        $tuk_address = '';
        $tuk_telp = '';
        $tuk_logo = null;

        if(strtolower($project->authority) == 'pusat' || $project->authority == null) {
            $tuk = FeasibilityTestTeam::where('authority', 'Pusat')->first();
            $authority_big = 'PUSAT';
        } else if((strtolower($project->authority) === 'provinsi') && ($project->auth_province !== null)) {
            $tuk = FeasibilityTestTeam::where([['authority', 'Provinsi'],['id_province_name', $project->auth_province]])->first();
            if($tuk) {
                $authority = ucwords(strtolower('PROVINSI' . strtoupper($tuk->provinceAuthority->name)));
                $authority_big = 'PROVINSI' . strtoupper($tuk->provinceAuthority->name);
            }
        } else if((strtolower($project->authority) == 'kabupaten') && ($project->auth_district !== null)) {
            $tuk = FeasibilityTestTeam::where([['authority', 'Kabupaten/Kota'],['id_district_name', $project->auth_district]])->first();
            if($tuk) {
                $authority = ucwords(strtolower(strtoupper($tuk->districtAuthority->name)));
                $authority_big = strtoupper($tuk->districtAuthority->name);
            }
        }

        if($tuk) {
            $tuk_address = $tuk->address;
            $tuk_telp = $tuk->phone;
            $ketua = FeasibilityTestTeamMember::where([['id_feasibility_test_team', $tuk->id],['position', 'Ketua']])->first();
            if($ketua) {
                if($ketua->expertBank) {
                    $ketua_tuk_name = $ketua->expertBank->name;
                } else if($ketua->lukMember) {
                    $ketua_tuk_name = $ketua->lukMember->name;
                    $ketua_tuk_nip = $ketua_tuk_name->lukMember->nip ?? '';
                }
            }
            $tuk_logo = $tuk->logo;
        }

        if($authority_big == 'PUSAT') {
            $templateProcessor = new TemplateProcessor('template_berkas_adm_no.docx');
        } else {
            $templateProcessor = new TemplateProcessor('template_berkas_adm_no_tuk.docx');
            $templateProcessor->setValue('authority', $authority);
            if($tuk_logo) {
                $templateProcessor->setImageValue('logo_tuk', substr(str_replace('//', '/', $tuk_logo), 1));
            } else {
                $templateProcessor->setImageValue('logo_tuk', 'images/logo-klhk-doc.jpg');
            }
        }

        $templateProcessor->setValue('docs_date', $docs_date);
        $templateProcessor->setValue('pemrakarsa', $project->initiator->name);
        $templateProcessor->setValue('pemrakarsa_address', $project->initiator->address);
        $templateProcessor->setValue('project_title', $project->project_title);
        $templateProcessor->setValue('project_address', $project_address);
        $templateProcessor->setValue('ketua_tuk_name', $ketua_tuk_name);
        $templateProcessor->setValue('ketua_tuk_nip', $ketua_tuk_nip);
        $templateProcessor->setValue('document_type', 'kerangka acuan');
        $templateProcessor->setValue('authority_big', $authority_big);
        $templateProcessor->setValue('tuk_address', $tuk_address);
        $templateProcessor->setValue('tuk_telp', $tuk_telp);

        $notesTable = new Table();
        $notesTable->addRow();
        $cell = $notesTable->addCell();
        Html::addHtml($cell, $this->replaceHtmlList($verification->notes));

        $templateProcessor->setComplexBlock('notes', $notesTable);
        $templateProcessor->saveAs(Storage::disk('public')->path('adm-no/hasil-adm-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));

        return strtolower(str_replace('/', '-', $project->project_title));
    }

    private function removeNestedParagraph($data)
    {
        $old_data = $data;
        $new_data = null;

        while(true) {
            $new_data = preg_replace('/(.*<p>)(((?!<\/p>).)*?)(<p>)(.*?)(<\/p>)(.*)/', '\1\2\5\7', $old_data);
            if($new_data == $old_data) {
                break;
            } else {
                $old_data = $new_data;
            }
        }

        return $new_data;
    }

    private function replaceHtmlList($data)
    {
        if($data) {
            return str_replace('</ul>', '', str_replace('<ul>', '', str_replace('<li>', '', str_replace('</li>', '<br/>', str_replace('</ol>', '', str_replace('<ol>', '' ,$this->removeNestedParagraph($data)))))));
        } else {
            return '';
        }
    }
}
