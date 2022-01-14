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
        $report->person_responsible = $data['person_responsible'];
        $report->location = $data['location'];
        $report->position = $data['position'];
        $report->id_feasibility_test_team = $data['id_feasibility_test_team'];
        $report->project_name = $data['project_name'];
        $report->id_initiator = $data['id_initiator'];
        $report->notes = $data['notes'];
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
            'invitations' => $invitations,
            'notes' => null
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
            'invitations' => $invitations,
            'notes' => $report->notes
        ];

        return $data;
    }

    private function getDocs($id_project) {
        $project = Project::findOrFail($id_project);
        $meeting = MeetingReport::select('id', 'id_project', 'id_feasibility_test_team', 'updated_at', 'location', 'meeting_date', 'meeting_time', 'notes', 'position')->where([['id_project', $id_project],['document_type', 'ka']])->first();
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
        $authority = '';
        $authority_big = '';
        $tuk_address = '';
        $ketua_tuk_name = '';
        $ketua_tuk_nip = '';
        $ketua_tuk_position = '';


        if($meeting->id_feasibility_test_team) {
            $team = FeasibilityTestTeam::find($meeting->id_feasibility_test_team);
            if($team) {
                if($team->authority == 'Pusat') {
                    $authority_big = 'PUSAT';
                    $authority = 'Pusat';
                } else if($team->authority == 'Provinsi') {
                    $authority_big = 'PROVINSI' . strtoupper($team->provinceAuthority->name);
                    $authority = 'Provinsi ' . ucwords(strtolower($team->provinceAuthority->name)); 
                } else if($team->authority == 'Kabupaten/Kota') {
                    $authority_big = strtoupper($team->districtAuthority->name);
                    $authority = ucwords(strtolower($team->districtAuthority->name));
                }

                $tuk_address = $team->address;

                // KETUA TUK
                $ketua_tuk = FeasibilityTestTeamMember::where([['id_feasibility_test_team', $team->id],['position', 'Ketua']])->first();
                if($ketua_tuk) {
                    if($ketua_tuk->expertBank) {
                        $ketua_tuk_name = $ketua_tuk->expertBank->name;
                    } else if($ketua_tuk->lukMember) {
                        $ketua_tuk_name = $ketua_tuk->lukMember->name;
                        $ketua_tuk_nip = $ketua_tuk->lukMember->nip ? 'NIP. ' . $ketua_tuk->lukMember->nip : '';
                        $ketua_tuk_position = $ketua_tuk->lukMember->position;
                    }
                }


            }

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

        return [
            'project_title' => $project->project_title,
            'project_title_big' => strtoupper($project->project_title),
            'pemrakarsa' => $project->initiator->name,
            'pemrakarsa_big' => strtoupper($project->initiator->name),
            'pic' => $project->initiator->pic,
            'pic_position' => $meeting->position,
            'ketua_tuk_position' => $ketua_tuk_position,
            'authority' => $authority,
            'authority_big' => $authority_big,
            'tuk_member' => $member,
            'notes' => $meeting->notes,
            'ketua_tuk_name' => $ketua_tuk_name,
            'meeting_date' => $meeting_date,
            'meeting_location' => $meeting_location,
            'year' => date('Y', strtotime($meeting->updated_at))
        ];
    }
}
