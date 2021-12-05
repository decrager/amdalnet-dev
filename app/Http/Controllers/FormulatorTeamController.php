<?php

namespace App\Http\Controllers;

use App\Entity\EnvironmentalExpert;
use App\Entity\Formulator;
use App\Entity\FormulatorTeam;
use App\Entity\FormulatorTeamMember;
use App\Entity\Lpjp;
use App\Entity\Project;
use App\Http\Resources\FormulatorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormulatorTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->type && $request->type == 'project') {
            $project = Project::findOrFail($request->idProject);
            return 'Tim Penyusun ' . $project->project_title;
        }

        if($request->type && $request->type == 'lpjp') {
            $lpjp = Lpjp::where([['date_start', '<=', date('Y-m-d H:i:s')],['date_end', '>=', date('Y-m-d H:i:s')]])->get();
            return $lpjp;
        }

        if ($request->type && $request->type == 'search') {
            $formulator = new Collection(Formulator::where([
                [DB::raw('lower(name)'), 'LIKE', '%' . strtolower($request->search) . '%'],
                ['date_start', '<=', date('Y-m-d H:i:s')], ['date_end', '>=', date('Y-m-d H:i:s')]
            ])
                ->orWhere([
                    [DB::raw('lower(name)'), 'LIKE', '%' . strtolower($request->search) . '%'],
                    ['date_start', null], ['date_end', '>=', date('Y-m-d H:i:s')]
                ])->get());
            // $expert = new Collection(EnvironmentalExpert::where(DB::raw('lower(name)'), 'LIKE', '%' . strtolower($request->search) . '%')->get());
            // $result = $formulator->merge($expert);
            return $formulator;
        } else if ($request->type && $request->type == 'all') {
            $formulator = new Collection(Formulator::where([
                ['date_start', '<=', date('Y-m-d H:i:s')], ['date_end', '>=', date('Y-m-d H:i:s')]
            ])
                ->orWhere([
                    ['date_start', null], ['date_end', '>=', date('Y-m-d H:i:s')]
                ])->get());
            $expert = new Collection(EnvironmentalExpert::all());
            $result = $formulator->merge($expert);
            return $result;
        } else {
            return FormulatorResource::collection(FormulatorTeam::select('formulator_teams.*')->where(
                function ($query) use ($request) {
                    return $request->lpjpId ? $query->where('projects.id_lpjp', $request->lpjpId) : '';
                }
            )->leftJoin('projects', 'projects.id', '=', 'formulator_teams.id_project')->paginate($request->limit));
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
        //validate request
        $validator = Validator::make(
            $request->all(),
            [
                'members'  => 'required'
            ],
            [
                'members.required' => 'Daftar Anggota Belum Ditambah'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first('members')], 403);
        } else {
            $params = $request->all();

            DB::beginTransaction();
            //create formulator team
            $team = new FormulatorTeam();
            $team->id_team = uniqid();
            $team->name = $params['name'];
            $team->id_project = $params['id_project'];
            $team->save();


            // create team members
            for ($i = 0; $i < count($params['members']); $i++) {
                $teamMember = new FormulatorTeamMember();
                $teamMember->id_formulator_team = $team->id;
                $teamMember->position = $params['members'][$i]['position'];
                if ($params['members'][$i]['type'] == 'expert') {
                    $teamMember->id_expert = $params['members'][$i]['id'];
                } else {
                    $teamMember->id_formulator = $params['members'][$i]['id'];
                }
                $teamMember->save();
            }

            if (!$team->id) {
                DB::rollback();
            } else {
                DB::commit();
            }

            return response()->json(['message' => 'success']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\FormulatorTeam  $formulatorTeam
     * @return \Illuminate\Http\Response
     */
    public function show(FormulatorTeam $formulatorTeam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\FormulatorTeam  $formulatorTeam
     * @return \Illuminate\Http\Response
     */
    public function edit(FormulatorTeam $formulatorTeam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\FormulatorTeam  $formulatorTeam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormulatorTeam $formulatorTeam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\FormulatorTeam  $formulatorTeam
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormulatorTeam $formulatorTeam)
    {
        //
    }

    public function getAll()
    {
        $getData = DB::table('formulator_teams')->get();
        return response()->json($getData);
    }
}
