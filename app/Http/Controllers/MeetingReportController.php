<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBankTeam;
use App\Entity\ExpertBankTeamMember;
use App\Entity\Initiator;
use App\Entity\MeetingReport;
use App\Entity\MeetingReportInvitation;
use App\Entity\Project;
use App\Entity\TestingMeeting;
use Illuminate\Http\Request;

class MeetingReportController extends Controller
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

        if($request->project) {
            return Project::whereHas('testingMeeting')->get();
        }

        if($request->idProject) {
            // Check if meeting report exist
            $report = MeetingReport::where('id_project', $request->idProject)->first();
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
        $data = $request->reports;

        // Save meetings
        $report = null;
        if($data['type'] == 'new') {
            $report = new MeetingReport();
            $report->id_project = $request->idProject;
            $report->id_testing_meeting = $data['id_testing_meeting'];
        } else {
            $report = MeetingReport::where('id_project', $request->idProject)->first();
        }


        $report->meeting_date = $data['meeting_date'];
        $report->meeting_time = $data['meeting_time'];
        $report->person_responsible = $data['person_responsible'];
        $report->location = $data['location'];
        $report->position = $data['position'];
        $report->expert_bank_team_id = $data['expert_bank_team_id'];
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
            $invitation->id_expert_bank_team_member = $data['invitations'][$i]['type'] == 'tuk' ? $data['invitations'][$i]['id'] : null;
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
        $meeting = TestingMeeting::where('id_project', $id_project)->first();

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
            'type' => 'new',
            'id_project' => $id_project,
            'id_testing_meeting' => $meeting->id,
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

    private function getExistReport($id_project) {
        $report = MeetingReport::where('id_project', $id_project)->first();

        $invitations = [];

        if($report->invitations->first()) {
            foreach($report->invitations as $i) {
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
            'id_testing_meeting' => $report->id_testing_meeting,
            'id_initiator' => $report->id_initiator,
            'meeting_date' => $report->meeting_date,
            'meeting_time' => $report->meeting_time,
            'person_responsible' => $report->person_responsible,
            'location' => $report->location,
            'position' => $report->position,
            'expert_bank_team_id' => $report->expert_bank_team_id,
            'project_name' => $report->project_name,
            'invitations' => $invitations
        ];

        return $data;
    }
}
