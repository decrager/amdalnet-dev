<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBankTeam;
use App\Entity\ExpertBankTeamMember;
use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\Initiator;
use App\Entity\Project;
use App\Entity\TestingMeeting;
use App\Entity\TestingMeetingInvitation;
use Illuminate\Http\Request;

class TestingMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

        if($request->idProject) {
            // Check if meeting exist
            $meetings = TestingMeeting::where([['id_project', $request->idProject], ['document_type', 'ka']])->first();
            if($meetings) {
                return $this->getExistMeetings($request->idProject);
            } else {
                return $this->getFreshMeetings($request->idProject);
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
        $data = $request->meetings;

        // Save meetings
        $meeting = null;
        $oldTeamId = null;
        if($data['type'] == 'new') {
            $meeting = new TestingMeeting();
            $meeting->id_project = $request->idProject;
            $meeting->document_type = 'ka';
        } else {
            $meeting = TestingMeeting::where([['id_project', $request->idProject],['document_type', 'ka']])->first();
            $oldTeamId = $meeting->id_feasibility_test_team;
        }


        $meeting->meeting_date = $data['meeting_date'];
        $meeting->meeting_time = $data['meeting_time'];
        $meeting->person_responsible = $data['person_responsible'];
        $meeting->location = $data['location'];
        $meeting->position = $data['position'];
        $meeting->id_feasibility_test_team = $data['id_feasibility_test_team'];
        $meeting->project_name = $data['project_name'];
        $meeting->id_initiator = $data['id_initiator'];
        $meeting->save();

        // Delete existing invitations if expert bank team is different
        if($data['type'] == 'update') {
            if($oldTeamId != $data['id_feasibility_test_team']) {
                TestingMeetingInvitation::where([['id_testing_meeting', $meeting->id]])->delete();           
            }
        }

        // Save meetings invitation members
        for($i = 0; $i < count($data['invitations']); $i++) {
            if($data['type'] == 'new') {
                $invitation = new TestingMeetingInvitation();
                $invitation->id_feasibility_test_team_member = $data['invitations'][$i]['type'] == 'tuk' ? $data['invitations'][$i]['id'] : null;
                $invitation->id_testing_meeting = $meeting->id;

                if($data['invitations'][$i]['type'] == 'other') {
                    $invitation->role = $data['invitations'][$i]['role'];
                    $invitation->name = $data['invitations'][$i]['name'];
                    $invitation->email = $data['invitations'][$i]['email'];
                }
    
                $invitation->save();
            } else {
                $invitation = new TestingMeetingInvitation();

                if($data['invitations'][$i]['type'] == 'tuk' && $oldTeamId == $data['id_feasibility_test_team']) {
                    continue;
                }

                if($data['invitations'][$i]['type'] == 'other') {
                    $invitation = TestingMeetingInvitation::where('email', $data['invitations'][$i]['email'])->first();

                    if(!$invitation) {
                        $invitation = new TestingMeetingInvitation();
                    }

                    $invitation->role = $data['invitations'][$i]['role'];
                    $invitation->name = $data['invitations'][$i]['name'];
                    $invitation->email = $data['invitations'][$i]['email'];
                }

                $invitation->id_feasibility_test_team_member = $data['invitations'][$i]['type'] == 'tuk' ? $data['invitations'][$i]['id'] : null;
                $invitation->id_testing_meeting = $meeting->id;
                $invitation->save();
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

    private function getFreshMeetings($id_project) {
        $project = Project::findOrFail($id_project);

        $data = [
            'type' => 'new',
            'id_project' => $id_project,
            'id_initiator' => $project->initiator->id,
            'meeting_date' => null,
            'meeting_time' => null,
            'person_responsible' => $project->initiator->pic,
            'location' => null,
            'position' => null,
            'expert_bank_team_id' => null,
            'id_feasibility_test_team' => null,
            'project_name' => $project->project_title,
            'invitations' => []
        ];

        return $data;
    }

    private function getExistMeetings($id_project) {
        $meeting = TestingMeeting::where([['id_project', $id_project],['document_type', 'ka']])->first();

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
            'type' => 'update',
            'id_project' => $id_project,
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
}
