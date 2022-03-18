<?php

namespace App\Http\Controllers;

use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TukProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $team_id = $this->getIdTeamByMemberEmail(Auth::user()->email);
        $team = FeasibilityTestTeam::findOrFail($team_id);
        
        return Project::select('id', 'registration_no', 'project_title', 'id_applicant', 'created_at', 'authority', 'auth_province', 'auth_district')
                    ->where(function($q) use($team) {
                        if($team->authority == 'Pusat') {
                            $q->where('authority', 'Pusat');
                        } else if($team->authority == 'Provinsi') {
                            $q->where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]]);
                        } else if($team->authority == 'Kabupaten/Kota') {
                            $q->where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]]);
                        }
                    })
                    ->where(function($q) use($request) {
                        if($request->search) {
                            $q->whereRaw("LOWER(project_title) LIKE '%" . strtolower($request->search) . "%'");
                        }
                    })
                    ->with(['address', 'initiator' => function($q) {
                        $q->select('id', 'name');
                    }])->orderBy('created_at', 'DESC')->paginate($request->limit);
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
        //
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

    private function getIdTeamByMemberEmail($email) {
        $id_team = null;

        $team_member = FeasibilityTestTeamMember::whereHas('lukMember', function($q) use($email) {
            $q->where('email', $email);
        })->first();

        if($team_member) {
            $id_team = $team_member->id_feasibility_test_team;
        } else {
            $team_member = FeasibilityTestTeamMember::whereHas('expertBank', function($q) use($email) {
                $q->where('email', $email);
            })->first();
            if($team_member) {
                $id_team = $team_member->id_feasibility_test_team;
            }
        }
        
        return $id_team;
    }
}
