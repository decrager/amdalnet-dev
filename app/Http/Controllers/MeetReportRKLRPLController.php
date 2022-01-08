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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MeetReportRKLRPLController extends Controller
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

        if($request->tuk_member) {
            $members = FeasibilityTestTeamMember::where('id_feasibility_test_team', $request->tuk_id)->get();
            $newMembers = [];
            
            foreach($members as $m) {
                $name = '';
                $email = '';
                $type_member = '';

                if($m->expertBank) {
                    $name = $m->expertBank->name;
                    $email = $m->expertBank->email;
                    $type_member = 'expert';
                } else if($m->lukMember) {
                    $name = $m->lukMember->name;
                    $email = $m->lukMember->email;
                    $type_member = 'employee';
                }

                $newMembers[] = [
                    'id' => $m->id,
                    'role' => $m->position,
                    'name' => $name,
                    'email' => $email,
                    'type' => 'tuk',
                    'type_member' => $type_member
                ];
            }

            return $newMembers;
        }

        if($request->project) {
            return Project::whereHas('testingMeeting')->get();
        }

        if($request->idProject) {
            // Check if meeting report exist
            $report = MeetingReport::where([['id_project', $request->idProject],['document_type', 'rkl-rpl']])->first();
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
        if ($request->formulir) {
            $project_title = strtolower(Project::findOrFail($request->idProject)->project_title);
            if (File::exists(storage_path('app/public/berita-acara/ba-rkl-rpl-' . $project_title . '.docx'))) {
                File::delete(storage_path('app/public/berita-acara/ba-rkl-rpl-' . $project_title . '.docx'));
            }

            if ($request->hasFile('docx')) {
                //create file
                $file = $request->file('docx');
                $name = '/berita-acara/ba-rkl-rpl-' . $project_title . '.docx';
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
            $report->document_type = 'rkl-rpl';
        } else {
            $report = MeetingReport::where([['id_project', $request->idProject],['document_type', 'rkl-rpl']])->first();
        }


        $report->meeting_date = $data['meeting_date'];
        $report->meeting_time = $data['meeting_time'];
        $report->person_responsible = $data['person_responsible'];
        $report->location = $data['location'];
        $report->position = $data['position'];
        $report->id_feasibility_test_team = $data['id_feasibility_test_team'];
        $report->project_name = $data['project_name'];
        $report->id_initiator = $data['id_initiator'];
        $report->save();

        // Delete existing invitations
        if($data['type'] == 'update') {
             MeetingReportInvitation::where('id_meeting_report', $report->id)->delete(); 
        }

        // Save meetings invitation members
        for($i = 0; $i < count($data['invitations']); $i++) {
            $invitation = new MeetingReportInvitation();
            $invitation->id_feasibility_test_team_member = $data['invitations'][$i]['type'] == 'tuk' ? $data['invitations'][$i]['id'] : null;
            $invitation->id_meeting_report = $report->id;

            if($data['invitations'][$i]['type'] == 'other') {
                $invitation->role = $data['invitations'][$i]['role'];
                $invitation->name = $data['invitations'][$i]['name'];
                $invitation->email = $data['invitations'][$i]['email'];
            }

            $invitation->save();

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

    private function getFreshReport($id_project) {
        $meeting = TestingMeeting::where([['id_project', $id_project],['document_type', 'rkl-rpl']])->first();

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

                    if($i->feasibilityTestTeamMember->id_expert_bank) {
                        $name = $i->feasibilityTestTeamMember->expertBank->name;
                        $email = $i->feasibilityTestTeamMember->expertBank->email;
                        $type_member = 'expert';
                    } else if($i->feasibilityTestTeamMember->id_luk_member) {
                        $name = $i->feasibilityTestTeamMember->lukMember->name;
                        $email = $i->feasibilityTestTeamMember->lukMember->email;
                        $type_member = 'employee';
                    }

                    $invitations[] = [
                        'id' => $i->id_feasibility_test_team_member,
                        'role' => $i->feasibilityTestTeamMember->position,
                        'name' => $name,
                        'email' => $email,
                        'type' => 'tuk',
                        'type_member' => $type_member
                    ];
                } else {
                    $invitations[] = [
                        'id' => $i->id,
                        'role' => $i->role,
                        'name' => $i->name,
                        'email' => $i->email,
                        'type' => 'other',
                        'type_member' => 'other'
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
            'id_initiator' => $meeting->project->initiator->id,
            'meeting_date' => $meeting->meeting_date,
            'meeting_time' => $meeting->meeting_time,
            'person_responsible' => $meeting->project->initiator->pic,
            'location' => $meeting->location,
            'position' => $meeting->position,
            'expert_bank_team_id' => $meeting->expert_bank_team_id,
            'id_feasibility_test_team' => $meeting->id_feasibility_test_team,
            'project_name' => $meeting->project->project_title,
            'invitations' => $invitations
        ];

        return $data;
    }

    private function getExistReport($id_project) {
        $report = MeetingReport::where([['id_project', $id_project],['document_type', 'rkl-rpl']])->first();

        $invitations = [];

        if($report->invitations->first()) {
            foreach($report->invitations as $i) {
                if($i->id_feasibility_test_team_member) {
                    $name = '';
                    $email = '';
                    $type_member = '';

                    if($i->feasibilityTestTeamMember->id_expert_bank) {
                        $name = $i->feasibilityTestTeamMember->expertBank->name;
                        $email = $i->feasibilityTestTeamMember->expertBank->email;
                        $type_member = 'expert';
                    } else if($i->feasibilityTestTeamMember->id_luk_member) {
                        $name = $i->feasibilityTestTeamMember->lukMember->name;
                        $email = $i->feasibilityTestTeamMember->lukMember->email;
                        $type_member = 'employee';
                    }

                    $invitations[] = [
                        'id' => $i->id_feasibility_test_team_member,
                        'role' => $i->feasibilityTestTeamMember->position,
                        'name' => $name,
                        'email' => $email,
                        'type' => 'tuk',
                        'type_member' => $type_member
                    ];
                } else {
                    $invitations[] = [
                        'id' => $i->id,
                        'role' => $i->role,
                        'name' => $i->name,
                        'email' => $i->email,
                        'type' => 'other',
                        'type_member' => 'other'
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
            'id_testing_meeting' => $report->id_testing_meeting,
            'id_initiator' => $report->project->initiator->id,
            'meeting_date' => $report->meeting_date,
            'meeting_time' => $report->meeting_time,
            'person_responsible' => $report->project->initiator->pic,
            'location' => $report->location,
            'position' => $report->position,
            'expert_bank_team_id' => $report->expert_bank_team_id,
            'id_feasibility_test_team' => $report->id_feasibility_test_team,
            'project_name' => $report->project->project_title,
            'invitations' => $invitations
        ];

        return $data;
    }

    private function getDocs($id_project) {
        Carbon::setLocale('id');
        $isExist = MeetingReport::where([['id_project', $id_project],['document_type', 'rkl-rpl']])->count();
        $project = Project::findOrFail($id_project);
        $report = null;

        if($isExist > 0) {
            $report = MeetingReport::where([['id_project', $id_project],['document_type', 'rkl-rpl']])->first();
        } else {
            $report = TestingMeeting::where([['id_project', $id_project],['document_type', 'rkl-rpl']])->first();
        }

        $district = '';
        $province = '';
        $ketua_tuk = '';
        $ketua_tuk_institution = '';
        $team_member = [];

        if($project->address) {
            if($project->address->first()) {
                $district = $project->address->first()->district;
                $province = 'provinsi ' . $project->address->first()->province;
            }
        }

        if($report->expert_bank_team_id) {
            $leader = ExpertBankTeamMember::where([['id_expert_bank_team', $report->expert_bank_team_id],['position', 'Ketua']])->first();
            if($leader) {
                $ketua_tuk = $leader->expertBank->name;
                $ketua_tuk_institution = $leader->expertBank->institution;
            }
        }

        if($report->invitations) {
            if($report->invitations->first()) {
                $alphabet_order = 'a';
                foreach($report->invitations as $inv) {
                    if($inv->id_expert_bank_team_member) {
                        $member = ExpertBankTeamMember::find($inv->id_expert_bank_team_member);
                        if($member) {
                            if($member->position != 'Ketua') {
                                $team_member[] = [
                                    'no' => $alphabet_order . '.',
                                    'name' => $member->expertBank->name . ' (Pakar '. $member->expertBank->expertise . ');'
                                ];
                                $alphabet_order++;
                            }
                        }
                    } else {
                        $team_member[] = [
                            'no' => $alphabet_order . '.',
                            'name' => 'Wakil dari ' . $inv->role . ';'
                        ];
                        $alphabet_order++;
                    }
                }
            }
        }

        $ids = [4, 1, 2, 3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function ($model) use ($ids) {
            return array_search($model->getKey(), $ids);
        });

        // DAMPAK PENTING HIPOTETIK
        $impactIdentifications = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal', 'is_hypothetical_significant', 'study_length_year', 'study_length_month')->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->get();
        $pra_konstruksi = [];
        $konstruksi = [];
        $operasi = [];
        $pasca_operasi = [];

        foreach ($stages as $s) {
            $total = 1;

            foreach ($impactIdentifications as $imp) {
                $ronaAwal = '';
                $component = '';

                // check stages
                $id_stages = null;

                if ($imp->subProjectComponent) {
                    if ($imp->subProjectComponent->id_project_stage) {
                        $id_stages = $imp->subProjectComponent->id_project_stage;
                    } else {
                        $id_stages = $imp->subProjectComponent->component->id_project_stage;
                    }

                    if ($id_stages == $s->id) {
                        if ($imp->subProjectRonaAwal) {
                            $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                            $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }

                $changeType = $imp->id_change_type ? $imp->changeType->name : '';

                $current_stage = strtolower(($s->name));
                $data = [
                    'no' => $total . '.',
                    'dph' => "$changeType $ronaAwal akibat $component",
                    'batas_waktu' => $imp->study_length_year . ' Tahun ' . $imp->study_length_month . ' Bulan'
                ];

                if($current_stage == 'pra konstruksi') {
                    $pra_konstruksi[] = $data;
                } else if($current_stage == 'konstruksi') {
                    $konstruksi[] = $data;
                }  else if($current_stage == 'operasi') {
                    $operasi[] = $data;
                } else {
                    $pasca_operasi[] = $data;
                }

                $total++;
            }
        }

        return [
            'document_type_big' => 'RENCANA PENGELOLAAN LINGKUNGAN HIDUP DAN RENCANA PEMANTAUAN LINGKUNGAN HIDUP (RKL-RPL)',
            'document_type' => 'Rencana Pengelolaan Lingkungan Hidup dan Rencana Pemantauan Lingkungan Hidup (RKL-RPL)',
            'project_title_big' => strtoupper($project->project_title),
            'project_address_big' => strtoupper($district . ' ' . $province),
            'pemrakarsa_big' => strtoupper($project->initiator->name),
            'meeting_date' =>  $report->meeting_date ? Carbon::createFromFormat('Y-m-d', $report->meeting_date)->isoFormat('dddd/D MMMM Y') : '',
            'meeting_location' => $report->location,
            'pemrakarsa' => $project->initiator->name,
            'pic' => $project->initiator->pic,
            'position' => $report->position,
            'leader' => $ketua_tuk,
            'team_member' => $team_member,
            'project_title' => ucwords(strtolower($project->project_title)),
            'project_address' => ucwords(strtolower($district . ' ' . $province)),
            'pemrakarsa' => $project->initiator->name,
            'meeting_lead' => $ketua_tuk,
            'meeting_lead_institution' => $ketua_tuk_institution,
            'pra_konstruksi' => $pra_konstruksi,
            'konstruksi' => $konstruksi,
            'operasi' => $operasi,
            'pasca_operasi' => $pasca_operasi 
        ];

    }
}
