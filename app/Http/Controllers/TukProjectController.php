<?php

namespace App\Http\Controllers;

use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\Project;
use App\Entity\TukProject;
use App\Entity\TukSecretaryMember;
use App\Laravue\Models\User;
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
        if($request->projectMembers) {
            return TukProject::where('id_project', $request->idProject)->get();
        }

        if($request->members) {
            $secretary_members = [];
            $tuk_members = [];

            $project = Project::findOrFail($request->projectId);
            
            // Get Team Id
            $where = [];
            if(strtolower($project->authority) == 'pusat') {
                $where = [['authority', 'Pusat']];
            } else if(strtolower($project->authority) == 'provinsi') {
                $where = [['authority', 'Provinsi'],['id_province_name', $project->auth_province]];
            } else if(strtolower($project->authority) == 'kabupaten') {
                $where = [['authority', 'Kabupaten/Kota'],['id_district_name', $project->auth_district]];
            }

            $team = FeasibilityTestTeam::where($where)->whereHas('member', function($q) {
                $q->where(function($q) {
                    $q->whereHas('lukMember', function($query) {
                        $query->where('email', Auth::user()->email);
                    })->orWhereHas('expertBank', function($query) {
                        $query->where('email', Auth::user()->email);
                    });
                })->where('position', 'Kepala Sekretariat');
            })->first();

            if($team) {
                $secretary_members = TukSecretaryMember::select('id', 'id_feasibility_test_team', 'name', 'institution', 'nik', 'email')
                                        ->where('id_feasibility_test_team', $team->id)
                                        ->get();
                $tuk_members = FeasibilityTestTeamMember::where('id_feasibility_test_team', $team->id)->with(['lukMember' => function($q) {
                    $q->select('id', 'name', 'institution', 'nik', 'email');
                }, 'expertBank' => function($q) {
                    $q->select('id', 'name', 'institution', 'email');
                }])->get();
            }
            
            return [
                'secretary_members' => $secretary_members,
                'tuk_members' => $tuk_members,
                'title' => $project->project_title
            ];
        }

        $team_id = $this->getIdTeamByMemberEmail(Auth::user()->email);
        $team = FeasibilityTestTeam::findOrFail($team_id);
        
        return Project::select('id', 'registration_no', 'project_title', 'id_applicant', 'created_at', 'authority', 'auth_province', 'auth_district')
                    ->where(function($q) use($team) {
                        if($team->authority == 'Pusat') {
                            $q->whereIn('authority', ['Pusat', 'pusat']);
                        } else if($team->authority == 'Provinsi') {
                            $q->where('auth_province', $team->id_province_name);
                            $q->whereIn('authority', ['Provinsi', 'provinsi']);
                        } else if($team->authority == 'Kabupaten/Kota') {
                            $q->where('auth_district', $team->id_district_name);
                            $q->whereIn('authority', ['Kabupaten', 'kabupaten']);
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
        $id_project = $request->idProject;
        $members = $request->members;
        $deleted_members = $request->deletedMembers;
        
        if(count($members) > 0) {
            for($i = 0; $i < count($members); $i++) {
                $member = null;
                if($members[$i]['id']) {
                    $member = TukProject::findOrFail($members[$i]['id']);
                } else {
                    $member = new TukProject();
                    $member->id_project = $id_project;
                    $explode = explode('-', $members[$i]['idType']);
                    if($explode[1] == 'tuk') {
                        $member->id_feasibility_test_team_member = $explode[0];
                    } else if($explode[1] == 'secretary') {
                        $member->id_tuk_secretary_member = $explode[0];
                    }

                     // === ADD ID USER === //
                     $user = User::where('email', $members[$i]['email'])->first();
                     if($user) {
                         $member->id_user = $user->id;
                     }
                }

                $member->role = $members[$i]['role'];
                $member->save();
            }
        }

        if(count($deleted_members) > 0) {
            TukProject::whereIn('id', $deleted_members)->delete();
        }

        return response()->json(['message' => 'Data successfully saved']);
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
