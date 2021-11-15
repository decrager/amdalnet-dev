<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBankTeam;
use App\Entity\ExpertBankTeamMember;
use App\Entity\Initiator;
use App\Entity\TestingMeeting;
use App\Entity\TestingMeetingInvitation;
use Dflydev\DotAccessData\Data;
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
            return ExpertBankTeam::all();
        }

        if($request->tuk_member) {
            $members = ExpertBankTeamMember::where('id_expert_bank_team', $request->tuk_id)->get();
            $newMembers = [];
            
            foreach($members as $m) {
                $newMembers[] = [
                    'id' => $m->id,
                    'role' => $m->position,
                    'name' => $m->expertBank->name,
                    'email' => $m->expertBank->email,
                    'type' => 'tuk'
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
        $oldExpertBankTeamId = null;
        if($data['type'] == 'new') {
            $meeting = new TestingMeeting();
            $meeting->id_project = $request->idProject;
            $meeting->document_type = 'ka';
        } else {
            $meeting = TestingMeeting::where([['id_project', $request->idProject],['document_type', 'ka']])->first();
            $oldExpertBankTeamId = $meeting->expert_bank_team_id;
        }


        $meeting->meeting_date = $data['meeting_date'];
        $meeting->meeting_time = $data['meeting_time'];
        $meeting->person_responsible = $data['person_responsible'];
        $meeting->location = $data['location'];
        $meeting->position = $data['position'];
        $meeting->expert_bank_team_id = $data['expert_bank_team_id'];
        $meeting->project_name = $data['project_name'];
        $meeting->id_initiator = $data['id_initiator'];
        $meeting->save();

        // Delete existing invitations if expert bank team is different
        if($data['type'] == 'update') {
            if($oldExpertBankTeamId != $data['expert_bank_team_id']) {
                TestingMeetingInvitation::where([['id_testing_meeting', $meeting->id], ['id_expert_bank_team_member', '!=', null]])->delete();           
            }
        }

        // Save meetings invitation members
        for($i = 0; $i < count($data['invitations']); $i++) {
            if($data['type'] == 'new') {
                $invitation = new TestingMeetingInvitation();
                $invitation->id_expert_bank_team_member = $data['invitations'][$i]['type'] == 'tuk' ? $data['invitations'][$i]['id'] : null;
                $invitation->id_testing_meeting = $meeting->id;

                if($data['invitations'][$i]['type'] == 'other') {
                    $invitation->role = $data['invitations'][$i]['role'];
                    $invitation->name = $data['invitations'][$i]['name'];
                    $invitation->email = $data['invitations'][$i]['email'];
                }
    
                $invitation->save();
            } else {
                $invitation = new TestingMeetingInvitation();

                if($data['invitations'][$i]['type'] == 'tuk' && $oldExpertBankTeamId == $data['expert_bank_team_id']) {
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

                $invitation->id_expert_bank_team_member = $data['invitations'][$i]['type'] == 'tuk' ? $data['invitations'][$i]['id'] : null;
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
        $data = [
            'type' => 'new',
            'id_project' => $id_project,
            'id_initiator' => null,
            'meeting_date' => null,
            'meeting_time' => null,
            'person_responsible' => null,
            'location' => null,
            'position' => null,
            'expert_bank_team_id' => null,
            'project_name' => null,
            'invitations' => []
        ];

        return $data;
    }

    private function getExistMeetings($id_project) {
        $meeting = TestingMeeting::where([['id_project', $id_project],['document_type', 'ka']])->first();

        $invitations = [];

        if($meeting->invitations->first()) {
            foreach($meeting->invitations as $i) {
                if($i->id_expert_bank_team_member) {
                    $invitations[] = [
                        'id' => $i->id_expert_bank_team_member,
                        'role' => $i->expertBankTeamMember->position,
                        'name' => $i->expertBankTeamMember->expertBank->name,
                        'email' => $i->expertBankTeamMember->expertBank->email,
                        'type' => 'tuk'
                    ];
                } else {
                    $invitations[] = [
                        'id' => $i->id,
                        'role' => $i->role,
                        'name' => $i->name,
                        'email' => $i->email,
                        'type' => 'other'
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
            'id_initiator' => $meeting->id_initiator,
            'meeting_date' => $meeting->meeting_date,
            'meeting_time' => $meeting->meeting_time,
            'person_responsible' => $meeting->person_responsible,
            'location' => $meeting->location,
            'position' => $meeting->position,
            'expert_bank_team_id' => $meeting->expert_bank_team_id,
            'project_name' => $meeting->project_name,
            'invitations' => $invitations
        ];

        return $data;
    }
}
