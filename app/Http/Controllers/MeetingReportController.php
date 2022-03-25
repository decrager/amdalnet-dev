<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBankTeam;
use App\Entity\ExpertBankTeamMember;
use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\ImpactIdentificationClone;
use App\Entity\Initiator;
use App\Entity\MeetingReport;
use App\Entity\MeetingReportInvitation;
use App\Entity\Project;
use App\Entity\ProjectStage;
use App\Entity\TestingMeeting;
use App\Entity\TukSecretaryMember;
use App\Utils\Html;
use App\Utils\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MeetingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->docs) {
            return $this->getDocs($request->idProject);
        }

        if($request->pemrakarsa) {
            return Initiator::where('user_type', 'Pemrakarsa')->get();
        }

        if($request->expert_bank_team) {
            return FeasibilityTestTeam::with(['provinceAuthority', 'districtAuthority'])->get();
        }

        if($request->tukMember) {
            return $this->getTukMember($request->idProject);
        }

        if($request->project) {
            return Project::whereHas('testingMeeting')->get();
        }

        if($request->idProject) {
            // Check if meeting report exist
            $report = MeetingReport::where([['id_project', $request->idProject],['document_type', 'ka']])->first();
            if($report) {
                return $this->getExistReport($request->idProject);
            } else {
                return $this->getFreshReport($request->idProject);
            }
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
        if($request->file) {
            $data = $request->all();
            $validator = \Validator::make($data, [
                'dokumen_file' => 'max:1024'
            ],[
                'dokumen_file.max' => 'Ukuran file tidak boleh melebihi 1 mb'
            ]);

            if($validator->fails()) {
                return response()->json(['errors' => $validator->messages()]);
            }
            
            $project = Project::findOrFail($request->idProject);

            if($request->hasFile('dokumen_file')) {
                $file = $request->file('dokumen_file');
                $name = '/berita-acara-ka/' . strtolower($project->project_title) . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
    
                $meeting_report = MeetingReport::where([['id_project', $request->idProject], ['document_type', 'ka']])->first();
                $meeting_report->file = Storage::url($name);
                $meeting_report->save();

            } else {
                return response()->json(['errors' => ['dokumen_file' => ['Dokumen Tidak Valid']]]);
            }

            // === WORKFLOW === //
            // if($project->marking == 'amdal.form-ka-ba-drafting') {
            //     $project->workflow_apply('sign-amdal-form-ka-ba');
            //     $project->save();
            // }

            return response()->json(['errors' => null, 'name' => $meeting_report->file]);
        }

        if ($request->formulir) {
            $project_title = strtolower(Project::findOrFail($request->idProject)->project_title);
            if (File::exists(storage_path('app/public/berita-acara/ba-ka-andal-' . $project_title . '.docx'))) {
                File::delete(storage_path('app/public/berita-acara/ba-ka-andal-' . $project_title . '.docx'));
            }

            if ($request->hasFile('docx')) {
                //create file
                $file = $request->file('docx');
                $name = '/berita-acara/ba-ka-andal-' . $project_title . '.docx';
                $file->storePubliclyAs('public', $name);

                return response()->json(['message' => 'success']);
            }

            return;
        }

        $data = $request->reports;

        // Save meetings
        $report = null;
        if($data['type'] == 'new') {
            $report = new MeetingReport();
            $report->id_project = $request->idProject;
            $report->id_testing_meeting = $data['id_testing_meeting'];
            $report->document_type = 'ka';
        } else {
            $report = MeetingReport::where([['id_project', $request->idProject],['document_type', 'ka']])->first();
        }


        $report->meeting_date = $data['meeting_date'];
        $report->meeting_time = $data['meeting_time'];
        $report->location = $data['location'];
        $report->notes = $data['notes'];
        $report->save();

        // Delete invitations
        if(count($data['deleted_invitations']) > 0) {
            MeetingReportInvitation::whereIn('id', $data['deleted_invitations'])->delete();
        }

        // Save meetings invitation members
        for($i = 0; $i < count($data['invitations']); $i++) {
            $invitation = null;

            if($data['invitations'][$i]['id'] == null) {
                $invitation = new MeetingReportInvitation();
                $invitation->id_meeting_report = $report->id;
            } else {
                $invitation = MeetingReportInvitation::findOrFail($data['invitations'][$i]['id']);
            }

            if($data['invitations'][$i]['type'] == 'Ketua TUK' || $data['invitations'][$i]['type'] == 'Anggota TUK') {
                $invitation->id_feasibility_test_team_member = $data['invitations'][$i]['tuk_member_id'];
            } else if($data['invitations'][$i]['type'] == 'Anggota Sekretariat TUK') {
                $invitation->id_tuk_secretary_member = $data['invitations'][$i]['tuk_member_id'];
            } else if($data['invitations'][$i]['type'] == 'institution' || $data['invitations'][$i]['type'] == 'other') {
                $invitation->role = $data['invitations'][$i]['role'];
                $invitation->name = $data['invitations'][$i]['name'];
                $invitation->email = $data['invitations'][$i]['email'];
                $invitation->institution = $data['invitations'][$i]['institution'];

                if($data['invitations'][$i]['type'] == 'institution') {
                    $invitation->id_government_institution = $data['invitations'][$i]['id_government_institution'];
                }
            }

            $invitation->save();

        }

        // === WORKFLOW === //
        // $project = Project::findOrFail($request->idProject);
        // if($project->marking == 'amdal.form-ka-approved') {
        //     $project->workflow_apply('draft-amdal-form-ka-ba');
        //     $project->save();
        // }

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

    private function getFreshReport($id_project) {
        $meeting = TestingMeeting::where([['id_project', $id_project],['document_type', 'ka']])->first();

        if(!$meeting) {
            return [
                'type' => 'notexist'
            ];
        }

        $invitations = [];

        if($meeting->invitations->first()) {
            foreach($meeting->invitations as $i) {
                if($i->id_feasibility_test_team_member) {
                    $name = '';
                    $email = '';
                    $type_member = '';
                    $institution = '-';

                    if($i->feasibilityTestTeamMember->id_expert_bank) {
                        $name = $i->feasibilityTestTeamMember->expertBank->name;
                        $email = $i->feasibilityTestTeamMember->expertBank->email;
                        $institution = $i->feasibilityTestTeamMember->expertBank->institution;
                    } else if($i->feasibilityTestTeamMember->id_luk_member) {
                        $name = $i->feasibilityTestTeamMember->lukMember->name;
                        $email = $i->feasibilityTestTeamMember->lukMember->email;
                        $institution = $i->feasibilityTestTeamMember->lukMember->institution;
                    }

                    if($i->feasibilityTestTeamMember->position == 'Ketua') {
                        $type_member = 'Ketua TUK';
                    } else {
                        $type_member = 'Anggota TUK';
                    }

                    $invitations[] = [
                        'id' => null,
                        'role' => $type_member,
                        'name' => $name,
                        'email' => $email,
                        'type' => $type_member,
                        'type_member' => $type_member,
                        'institution' => $institution,
                        'tuk_member_id' => $i->id_feasibility_test_team_member 
                    ];
                } else if($i->id_tuk_secretary_member) {
                    $invitations[] = [
                        'id' => null,
                        'role' => 'Anggota Sekretariat TUK',
                        'name' => $i->tukSecretaryMember->name,
                        'email' => $i->tukSecretaryMember->email,
                        'type' => 'Anggota Sekretariat TUK',
                        'type_member' => 'Anggota Sekretariat TUK',
                        'institution' => $i->tukSecretaryMember->institution,
                        'tuk_member_id' => $i->id_tuk_secretary_member,
                    ];
                } else {
                    $invitations[] = [
                        'id' => null,
                        'role' => $i->role,
                        'name' => $i->name,
                        'email' => $i->email,
                        'type' => 'other',
                        'type_member' => 'other',
                        'institution' => $i->institution,
                        'id_government_institution' => $i->id_government_institution,
                    ];
                }
            }

            // Make expert bank team on top
            usort($invitations, function($a, $b) {
                if($a['type']==$b['type']) return 0;
                return $a['type'] < $b['type']?1:-1;
            });
        } 

        $data = [
            'type' => 'new',
            'id_project' => $id_project,
            'id_testing_meeting' => $meeting->id,
            'meeting_date' => $meeting->meeting_date,
            'meeting_time' => $meeting->meeting_time,
            'location' => $meeting->location,
            'expert_bank_team_id' => $meeting->expert_bank_team_id,
            'invitations' => $invitations,
            'notes' => null,
            'file' => null,
            'deleted_invitations' => []
        ];

        return $data;
    }

    private function getExistReport($id_project) {
        $report = MeetingReport::where([['id_project', $id_project],['document_type', 'ka']])->first();

        $invitations = [];

        if($report->invitations->first()) {
            foreach($report->invitations as $i) {
                if($i->id_feasibility_test_team_member) {
                    $name = '';
                    $email = '';
                    $type_member = '';
                    $institution = '-';

                    if($i->feasibilityTestTeamMember->id_expert_bank) {
                        $name = $i->feasibilityTestTeamMember->expertBank->name;
                        $email = $i->feasibilityTestTeamMember->expertBank->email;
                        $institution = $i->feasibilityTestTeamMember->expertBank->institution;
                    } else if($i->feasibilityTestTeamMember->id_luk_member) {
                        $name = $i->feasibilityTestTeamMember->lukMember->name;
                        $email = $i->feasibilityTestTeamMember->lukMember->email;
                        $institution = $i->feasibilityTestTeamMember->lukMember->institution;
                    }

                    if($i->feasibilityTestTeamMember->position == 'Ketua') {
                        $type_member = 'Ketua TUK';
                    } else {
                        $type_member = 'Anggota TUK';
                    }

                    $invitations[] = [
                        'id' => $i->id,
                        'role' => $type_member,
                        'name' => $name,
                        'email' => $email,
                        'type' => $type_member,
                        'type_member' => $type_member,
                        'institution' => $institution,
                        'tuk_member_id' => $i->id_feasibility_test_team_member,
                    ];
                } else if($i->id_tuk_secretary_member) {
                    $invitations[] = [
                        'id' => $i->id,
                        'role' => 'Anggota Sekretariat TUK',
                        'name' => $i->tukSecretaryMember->name,
                        'email' => $i->tukSecretaryMember->email,
                        'type' => 'Anggota Sekretariat TUK',
                        'type_member' => 'Anggota Sekretariat TUK',
                        'institution' => $i->tukSecretaryMember->institution,
                        'tuk_member_id' => $i->id_tuk_secretary_member,
                    ];
                } else {
                    $invitations[] = [
                        'id' => $i->id,
                        'role' => $i->role,
                        'name' => $i->name,
                        'email' => $i->email,
                        'type' => 'other',
                        'type_member' => 'other',
                        'institution' => $i->institution,
                        'id_government_institution' => $i->id_government_institution,
                    ];
                }
            }

            // Make expert bank team on top
            usort($invitations, function($a, $b) {
                if($a['type']==$b['type']) return 0;
                return $a['type'] < $b['type']?1:-1;
            });
        } 

        $data = [
            'type' => 'update',
            'id_project' => $id_project,
            'id_meeting_report' => $report->id_meeting_report,
            'meeting_date' => $report->meeting_date,
            'meeting_time' => $report->meeting_time,
            'location' => $report->location,
            'expert_bank_team_id' => $report->expert_bank_team_id,
            'invitations' => $invitations,
            'notes' => $report->notes,
            'file' => $report->file,
            'deleted_invitations' => []
        ];

        return $data;
    }

    private function getTukMember($id)
    {
        $project = Project::findOrFail($id);
        $newMembers = [];
        $tuk = null;

        if(strtolower($project->authority) == 'pusat' || $project->authority == null) {
            $tuk = FeasibilityTestTeam::where('authority', 'Pusat')->first();
        } else if((strtolower($project->authority) === 'provinsi') && ($project->auth_province !== null)) {
            $tuk = FeasibilityTestTeam::where([['authority', 'Provinsi'],['id_province_name', $project->auth_province]])->first();
        } else if((strtolower($project->authority) == 'kabupaten') && ($project->auth_district !== null)) {
            $tuk = FeasibilityTestTeam::where([['authority', 'Kabupaten/Kota'],['id_district_name', $project->auth_district]])->first();
        }

        if($tuk) {
            $members = FeasibilityTestTeamMember::where('id_feasibility_test_team', $tuk->id)->get();
            
            foreach($members as $m) {
                $name = '';
                $email = '';
                $type_member = '';
                $institution = '';
    
                if($m->expertBank) {
                    $name = $m->expertBank->name;
                    $email = $m->expertBank->email;
                    $institution = $m->expertBank->institution;
                    $type_member = 'expert';
                } else if($m->lukMember) {
                    $name = $m->lukMember->name;
                    $email = $m->lukMember->email;
                    $institution = $m->lukMember->institution;
                    $type_member = 'employee';
                }
    
                $newMembers[] = [
                    'id' => $m->id,
                    'role' => $m->position,
                    'name' => $name,
                    'email' => $email,
                    'institution' => $institution,
                    'type' => 'tuk',
                    'type_member' => $type_member
                ];
            }

            // === SECRETARY MEMBER === //
            $secretary = TukSecretaryMember::where('id_feasibility_test_team', $tuk->id)->get();
            foreach($secretary as $s) {
                $newMembers[] = [
                    'id' => $s->id,
                    'role' => 'Anggota Sekretariat',
                    'name' => $s->name,
                    'email' => $s->email,
                    'institution' => $s->institution,
                    'type' => 'tuk_secretary',
                    'type_member' => 'secretary-member'
                ];
            }
        }

        return $newMembers;
    } 

    private function getDocs($id_project) {
        if (!File::exists(storage_path('app/public/ba-ka/'))) {
            File::makeDirectory(storage_path('app/public/ba-ka/'));
        }

        $project = Project::findOrFail($id_project);
        $meeting = MeetingReport::select('id', 'id_project', 'updated_at', 'location', 'meeting_date', 'meeting_time', 'notes')->where([['id_project', $id_project],['document_type', 'ka']])->first();
        $invitations = MeetingReportInvitation::where('id_meeting_report', $meeting->id)->get();
        Carbon::setLocale('id');
        
        $docs_date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($meeting->updated_at)))->isoFormat('MMMM Y');

        $project_address = '';
        if($project->address) {
            if($project->address->first()) {
                $project_address = $project->address->first()->address . ' ' . ucwords(strtolower($project->address->first()->district)) . ' Provinsi ' . ucwords(strtolower($project->address->first()->prov));
            }
        }

        // Meeting
        $meeting_date = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($meeting->meeting_date)))->isoFormat('D, MMMM Y');
        $meeting_time = date('H:i', strtotime($meeting->meeting_time));
        $meeting_location = $meeting->location;

        // TUK
        $authority_real = '';
        $authority = '';
        $authority_big = '';
        $tuk_address = '';
        $ketua_tuk_name = '';
        $ketua_tuk_position = '';
        $tuk_telp = '';
        $tuk_logo = null;

        if(strtolower($project->authority) == 'pusat' || $project->authority == null) {
            $tuk = FeasibilityTestTeam::where('authority', 'Pusat')->first();
            $authority = 'Pusat';
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
            $authority_real = $tuk->authority;
            $ketua = FeasibilityTestTeamMember::where([['id_feasibility_test_team', $tuk->id],['position', 'Ketua']])->first();
            if($ketua) {
                if($ketua->expertBank) {
                    $ketua_tuk_name = $ketua->expertBank->name;
                } else if($ketua->lukMember) {
                    $ketua_tuk_name = $ketua->lukMember->name;
                }
            }
            $tuk_logo = $tuk->logo;
        }
        
        $member = [];
        $ahli = [];
        
        foreach($invitations as $i) {
            if($i->id_feasibility_test_team_member) {
                $tuk_member = FeasibilityTestTeamMember::find($i->id_feasibility_test_team_member);
                if($tuk_member) {
                    if($tuk_member->position != 'Ketua') {
                        if($tuk_member->expertBank) {
                            $member[] = [
                                'name' => count($member) + 1 . '. ' . $tuk_member->expertBank->name . ' (Pakar ' . $tuk_member->expertBank->expertise . ')'
                            ];
                        } else if($tuk_member->lukMember) {
                            $member[] = [
                                'name' => count($member) + 1 . '. ' . $tuk_member->lukMember->name . ', ' . $tuk_member->lukMember->position. ' ' . $tuk_member->lukMember->institution
                            ];
                        }
                    }
                }
            } else {
                if($i->role == 'Tenaga Ahli') {
                    $member[] = [
                        'name' => count($ahli) + 1 . '. ' . $i->name
                    ];
                }
            }
        }

        $templateProcessor = null;

        if($authority_real == 'Pusat') {
            $templateProcessor = new TemplateProcessor('template_berita_acara.docx');
        } else {
            $templateProcessor = new TemplateProcessor('template_berita_acara_tuk.docx');
            $templateProcessor->setValue('tuk_address', $tuk_address);
            $templateProcessor->setValue('tuk_telp', $tuk_telp);

            if($tuk_logo) {
                $templateProcessor->setImageValue('logo_tuk', substr(str_replace('//', '/', $tuk_logo), 1));
            } else {
                $templateProcessor->setImageValue('logo_tuk', 'images/logo-klhk-doc.jpg');
            }
        }

        $templateProcessor->setValue('project_title', $project->project_title);
        $templateProcessor->setValue('project_title_big', strtoupper($project->project_title));
        $templateProcessor->setValue('pemrakarsa', $project->initiator->name);
        $templateProcessor->setValue('pemrakarsa_big', strtoupper($project->initiator->name));
        $templateProcessor->setValue('pic', $project->initiator->pic);
        $templateProcessor->setValue('pic_position', $project->initiator->pic_role);
        $templateProcessor->setValue('ketua_tuk_position', $ketua_tuk_position);
        $templateProcessor->setValue('authority', $authority);
        $templateProcessor->setValue('authority_big', $authority_big);
        $templateProcessor->setValue('ketua_tuk_name', $ketua_tuk_name);
        $templateProcessor->setValue('meeting_date', $meeting_date);
        $templateProcessor->setValue('meeting_location', $meeting_location);
        $templateProcessor->setValue('year', date('Y', strtotime($meeting->updated_at)));
        $templateProcessor->cloneBlock('tuk_member', count($member), true, false, $member);

        $notesTable = new Table();
        $notesTable->addRow();
        $cell = $notesTable->addCell();
        Html::addHtml($cell, $meeting->notes);

        $templateProcessor->setComplexBlock('notes', $notesTable);
        $templateProcessor->saveAs(storage_path('app/public/ba-ka/ba-ka-' . strtolower($project->project_title) . '.docx'));

        return strtolower($project->project_title);
    }
}
