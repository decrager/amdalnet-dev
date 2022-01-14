<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entity\Project;

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
            ->join('formulator_teams', 'projects.id', '=', 'formulator_teams.id_project')
            ->leftJoin('formulator_team_members', 'formulator_teams.id', '=', 'formulator_team_members.id_formulator_team')
            ->join('formulators', 'formulators.id', '=', 'formulator_team_members.id_formulator')
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
}
