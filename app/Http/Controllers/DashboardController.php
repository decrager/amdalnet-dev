<?php

namespace App\Http\Controllers;

use App\Entity\District;
use App\Entity\FeasibilityTestTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entity\Project;
use App\Laravue\Models\User;

class DashboardController extends Controller
{
    //

    public function index(Request $request) {
      
    }   

    public function proposalCount(Request $request){
        // pemrakarsa
        $q = DB::table('projects')
        ->select('projects.required_doc', DB::raw('count(*) as total'));
        if ($request->initiatorId){
            return response($q->where('projects.id_applicant', $request->initiatorId)
            ->groupBy('projects.required_doc')->get());
        }

        if($request->formulatorId){
            $proposals = $q
            ->leftJoin('formulator_teams', 'projects.id', '=', 'formulator_teams.id_project')
            ->leftJoin('formulator_team_members', 'formulator_teams.id', '=', 'formulator_team_members.id_formulator_team')
            ->leftJoin('formulators', 'formulators.id', '=', 'formulator_team_members.id_formulator')
            ->where('formulators.id', $request->formulatorId)->groupBy('projects.required_doc')->get();
            return response($proposals);
        }

        return response($q->groupBy('projects.required_doc')->get());
    }

    public function latestActivities(Request $request){
        if($request->initiatorId) {
            return response(Project::with(['address'])
              ->where('id_applicant', $request->initiatorId)
              ->orderBy('created_at', 'desc')->paginate(3));
        }
        if($request->formulatorId){
            return response(Project::with(['address'])
            ->select('projects.*', 'project_address.*')
            ->join('formulator_teams', 'projects.id', '=', 'formulator_teams.id_project')
            ->leftJoin('formulator_team_members', 'formulator_teams.id', '=', 'formulator_team_members.id_formulator_team')
            ->leftjoin('formulators', 'formulators.id', '=', 'formulator_team_members.id_formulator')
            ->leftJoin('project_address', 'project_address.id_project', 'projects.id')
            ->where('formulators.id', $request->formulatorId)
            ->orderBy('projects.created_at', 'desc')->paginate(3));
        }

        return response(Project::with(['address'])
        ->select('projects.*', 'initiators.name as applicant')
        ->leftJoin('initiators', 'initiators.id', 'projects.id_applicant')
        ->orderBy('projects.created_at', 'desc')->paginate(5));
    }

    public function status(Request $request)
    {
        $total = 0;
        $accepted = 0;
        $on_progress = 0;
        $rejected = 0;

        $type = $request->type;
        $id_user = $request->id_user;

        if($type == 'tuk') {
            $user = User::find($id_user);
            if($user) {
                $team = $this->checkTuk($user);
                if($team) {
                    if($team->authority == 'Pusat') {
                        $total = Project::count();
                        $accepted = Project::whereHas('feasibilityTest')->count();
                        $on_progress = Project::whereDoesntHave('feasibilityTest')->count();
                    } else if($team->authority == 'Provinsi') {
                        // === GET DISTRICT === //
                        $districts = $this->getDistricts($team->id_province_name);

                        $query =  Project::where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]])
                                ->orWhere(function($q) use($districts) {
                                    $q->where('authority', 'district');
                                    $q->whereIn('auth_district', $districts);
                                });

                        $total = $query->count();
                        $accepted = $query->whereHas('feasibilityTest')->count();
                        $on_progress = $query->whereDoesntHave('feasibilityTest')->count();
                    } else if($team->authority == 'Kabupaten/Kota') {
                        $query = Project::where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]]);

                        $total = $query->count();
                        $accepted = $query->whereHas('feasibilityTest')->count();
                        $on_progress = $query->whereDoesntHave('feasibilityTest')->count();
                    } 
                }
            }
        }

        return [
            'total' => $total,
            'accepted' => $accepted,
            'on_progress' => $on_progress,
            'rejected' => $rejected
        ];
    }

    public function permitAuthority(Request $request)
    {
        $type = $request->type;
        $id_user = $request->id_user;

        $pusat = [
            'total' => 0,
            'show' => false
        ];

        $provinsi = [
            'total' => 0,
            'show' => false
        ];

        $kabupaten = [
            'total' => 0,
            'show' => false
        ];

        if($type == 'tuk') {
            $user = User::whereKey($id_user)->with('roles')->first();
            if($user) {
                $team = $this->checkTuk($user);
                if($team) {
                    if($team->authority == 'Pusat') {
                        $project = Project::where('authority', 'Pusat')->count();
                        $pusat = [
                            'total' => $project,
                            'show' => true
                        ];

                        $project = Project::where('authority', 'Provinsi')->count();
                        $provinsi = [
                            'total' => $project,
                            'show' => true
                        ];

                        $project = Project::where('authority', 'Kabupaten')->count();
                        $kabupaten = [
                            'total' => $project,
                            'show' => true
                        ];
                    }

                    if($team->authority == 'Provinsi') {
                        $project = Project::where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]])->count();
                        $provinsi = [
                            'total' => $project,
                            'show' => true
                        ];
                        
                        // === GET DISTRICT === //
                        $districts = $this->getDistricts($team->id_province_name);

                        $project = Project::where('authority', 'Kabupaten')->whereIn('auth_district', $districts)->count();
                        $kabupaten = [
                            'total' => $project,
                            'show' => true
                        ];
                    }

                    if($team->authority == 'Kabupaten/Kota') {
                        $project = Project::where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]])->count();
                        $kabupaten = [
                            'total' => $project,
                            'show' => true
                        ];
                    }
                }
            }
        }

        return [
            'pusat' => $pusat,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten
        ];
    }

    public function initiator(Request $request)
    {
        $type = $request->type;
        $id_user = $request->id_user;

        if($type == 'tuk') {
            $user = User::whereKey($id_user)->with('roles')->first();
            if($user) {
                $team = $this->checkTuk($user);
                if($team) {
                    $query = Project::select('id', 'id_applicant', 'created_at', 'authority', 'auth_province', 'auth_district')
                                    ->with(['initiator' => function($q) {
                                        $q->select('id', 'name');
                                    }, 'feasibilityTest' => function($q) {
                                        $q->select('id', 'id_project');
                                    }]);
                    if($team->authority == 'Pusat') {
                        return $query->orderBy('created_at', 'desc')->paginate($request->limit);
                    } else if($team->authority == 'Provinsi') {
                        $districts = $this->getDistricts($team->id_province_name);
                        return $query->where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]])
                                     ->orWhere(function($q) use($districts) {
                                         $q->where('authority', 'Kabupaten');
                                         $q->whereIn('auth_district', $districts);
                                     })
                                     ->orderBy('created_at', 'desc')
                                     ->paginate($request->limit);
                    } else if($team->authority == 'Kabupaten/Kota') {
                        return $query->where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]])
                                     ->orderBy('created_at', 'desc')
                                     ->paginate($request->limit);
                    }
                }
            }
        }
    }

    private function checkTuk($user)
    {
        return FeasibilityTestTeam::whereHas('member', function($q) use($user) {
            $q->whereHas('lukMember', function($query) use ($user) {
                $query->where('email', $user->email);
            })->orWhereHas('expertBank', function($query) use ($user) {
                $query->where('email', $user->email);
            });
            $q->where('position', 'Kepala Sekretariat');
        })->first();
    }

    private function getDistricts($id_province)
    {
        $districts = [];
        $districts = District::select('id','id_prov')->where('id_prov', $id_province)->get();
        foreach($districts as $d) {
            $districts[] = $d->id;
        }

        return $districts;
    }
}
