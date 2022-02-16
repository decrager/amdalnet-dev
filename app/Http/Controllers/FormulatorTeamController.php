<?php

namespace App\Http\Controllers;

use App\Entity\EnvironmentalExpert;
use App\Entity\Formulator;
use App\Entity\FormulatorTeam;
use App\Entity\FormulatorTeamMember;
use App\Entity\Lpjp;
use App\Entity\Project;
use App\Http\Resources\FormulatorResource;
use App\Laravue\Models\User;
use App\Notifications\FormulatorTeamAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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
        if($request->type && $request->type == 'formulator') {
            if($request->lpjp) {
                return Formulator::select('id', 'name', 'expertise', 'cv_file', 'cert_file', 'reg_no', 'membership_status', 'date_start', 'date_end')
                    ->where([['membership_status', '!=', 'TA'],['id_lpjp', $request->idLpjp],['date_start', '<=', date('Y-m-d H:i:s')], ['date_end', '>=', date('Y-m-d H:i:s')]])
                    ->orWhere([['membership_status', '!=', 'TA'],['id_lpjp', $request->idLpjp],['date_start', null], ['date_end', '>=', date('Y-m-d H:i:s')]])
                    ->orderBy('name')
                    ->get();
            }

            return Formulator::select('id', 'name', 'expertise', 'cv_file', 'cert_file', 'reg_no', 'membership_status', 'date_start', 'date_end')
                                ->where([['membership_status', '!=', 'TA'],['date_start', '<=', date('Y-m-d H:i:s')], ['date_end', '>=', date('Y-m-d H:i:s')]])->orWhere([['membership_status', '!=', 'TA'],['date_start', null], ['date_end', '>=', date('Y-m-d H:i:s')]])
                                ->orderBy('name')
                                ->get();
        }

        if($request->type && $request->type == 'project') {
            $project = Project::findOrFail($request->idProject);
            return [
                'name' => 'Tim Penyusun ' . $project->project_title,
                'type_team' => $project->type_formulator_team,
                'id_lpjp' => $project->id_lpjp
            ];
        }

        if($request->type && $request->type == 'lpjp') {
            $lpjp = Lpjp::where([['date_start', '<=', date('Y-m-d H:i:s')],['date_end', '>=', date('Y-m-d H:i:s')]])->get();
            return $lpjp;
        }

        if($request->type && $request->type == 'tim-penyusun') {
            $id_project = $request->idProject;
            $member = [];
            $formulatorTeamMember = FormulatorTeamMember::whereHas('team', function($q) use($id_project) {
                $q->where('id_project', $id_project);
            })->where([['id_formulator', '!=', null],['id_expert', null]])->get();

            $num = 1;
            foreach($formulatorTeamMember as $f) {
                $member[] = [
                    'num' => $num,
                    'id' => $f->id,
                    'db_id' => $f->id_formulator,
                    'name' => $f->formulator->name,
                    'value' => $f->formulator->name,
                    'type' => 'update',
                    'position' => $f->position,
                    'expertise' => $f->formulator->expertise,
                    'file' => $f->formulator->cv_file,
                    'certificate' => $f->formulator->cert_file,
                    'reg_no' => $f->formulator->reg_no,
                    'membership_status' => $f->formulator->membership_status
                ];

                $num++;
            }

            return $member;
        }

        if($request->type && $request->type == 'tim-ahli') {
            $id_project = $request->idProject;
            $member = [];
            $formulatorTeamMember = FormulatorTeamMember::whereHas('team', function($q) use($id_project) {
                $q->where('id_project', $id_project);
            })->where([['id_formulator', null],['id_expert', '!=', null]])->get();

            $num = 1;
            foreach($formulatorTeamMember as $f) {
                $member[] = [
                    'num' => $num,
                    'id' => $f->id,
                    'name' => $f->expert->name,
                    'type' => 'update',
                    'status' => $f->expert->status,
                    'expertise' => $f->expert->expertise,
                    'cv' => $f->expert->cv
                ];

                $num++;
            }

            return $member;
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
        if($request->type && $request->type == 'lpjp') {
            $validator = Validator::make(
                $request->all(),
                [
                    'lpjp'  => 'required',
                ],
                [
                    'lpjp.required' => 'Pilih Jenis Team'
                ]
            );

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->first('lpjp')], 403);
            } else {
                $project = Project::findOrFail($request->idProject);
                $project->id_lpjp = $request->lpjp;
                $project->type_formulator_team = 'lpjp';
                $project->save();

                return response()->json(['message' => 'success']);
            }
        }

        //validate request
        $validator = Validator::make(
            $request->all(),
            [
                'team_type'  => 'required',
            ],
            [
                'team_type.required' => 'Pilih Jenis Team'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first('team_type')], 403);
        } else {
            $params = $request->all();

            $receiver = array();

            // check if project team exist
            $team = FormulatorTeam::where('id_project', $request->idProject)->first();
            if(!$team) {
                $project= Project::findOrFail($request->idProject);
                $project->type_formulator_team = $params['team_type'];
                $project->save();

                //create formulator team
                $team = new FormulatorTeam();
                $team->id_team = uniqid();
                $team->name = 'Tim Penyusun ' . $project->project_title;
                $team->id_project = $params['idProject'];
                $team->save();
            }

            // create team members
            $members = json_decode($request->members, true);
            for ($i = 0; $i < count($members); $i++) {
                $teamMember = null;
                if($members[$i]['type'] == 'new') {
                    $teamMember = new FormulatorTeamMember();
                    $teamMember->id_formulator_team = $team->id;
                    $teamMember->id_formulator = $members[$i]['id'];
                } else {
                    $teamMember = FormulatorTeamMember::findOrFail($members[$i]['id']);
                }

                $teamMember->position = $members[$i]['position'];
                $teamMember->save();

                // add to list notification receiver
                $formulator = Formulator::find($members[$i]['id']);

                if($formulator){
                    $user = User::where('email', $formulator->email)->first();

                    if($user) {
                        array_push($receiver, $user);
                    }
                }
            }

            // create team members ahli
            $membersAhli = json_decode($request->membersAhli, true);
            for($a = 0; $a < count($membersAhli); $a++) {
                $teamMember = null;
                $ahli = null;
                if($membersAhli[$a]['type'] == 'new') {
                    $ahli = new EnvironmentalExpert();
                } else {
                    $teamMember = FormulatorTeamMember::findOrFail($membersAhli[$a]['id']);
                    $ahli = EnvironmentalExpert::findOrFail($teamMember->id_expert);
                }

                $ahli->name = $membersAhli[$a]['name'];
                $ahli->status = $membersAhli[$a]['status'];
                $ahli->expertise = $membersAhli[$a]['expertise'];

                // Upload CV
                $fileRequestName = 'file-' . $a;
                if($request->hasFile($fileRequestName)) {
                    $file = $request->file($fileRequestName);
                    $name = '/cv/' . uniqid() . '.' . $file->extension();
                    $file->storePubliclyAs('public', $name);

                    $ahli->cv = Storage::url($name);
               }

               $ahli->save();
               
               if($membersAhli[$a]['type'] == 'new') {
                   $teamMember = new FormulatorTeamMember();
                   $teamMember->id_formulator_team = $team->id;
                   $teamMember->id_expert = $ahli->id;
                   $teamMember->save();
               }
            }

            // Delete Team Penyusun
            $deletedPenyusun = json_decode($request->deletedPenyusun, true);
            for($c = 0; $c < count($deletedPenyusun); $c++) {
                FormulatorTeamMember::destroy($deletedPenyusun[$c]);
            }

            // Delete Team Penyusun
            $deletedAhli = json_decode($request->deletedAhli, true);
            for($b = 0; $b < count($deletedAhli); $b++) {
                FormulatorTeamMember::destroy($deletedAhli[$b]);
            }

            // send notif
            Notification::send($receiver, new FormulatorTeamAssigned($team));

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
