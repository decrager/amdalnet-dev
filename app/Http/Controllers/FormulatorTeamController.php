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
use Exception;
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
            $id_project = $request->idProject;
            if($request->lpjp) {
                return Formulator::select('id', 'name', 'email', 'expertise', 'cv_file', 'cert_file', 'reg_no', 'membership_status', 'date_start', 'date_end')
                    ->whereDoesntHave('teamMember', function($q) use($id_project) {
                        $q->whereHas('team', function($query) use($id_project) {
                            $query->where('id_project', $id_project);
                        });
                    })
                    ->whereHas('user')
                    ->where([['membership_status', '!=', 'TA'],['id_lpjp', $request->idLpjp],['date_start', '<=', date('Y-m-d H:i:s')], ['date_end', '>=', date('Y-m-d H:i:s')]])
                    ->orWhere([['membership_status', '!=', 'TA'],['id_lpjp', $request->idLpjp],['date_start', null], ['date_end', '>=', date('Y-m-d H:i:s')]])
                    ->orderBy('name')
                    ->get();
            }

            return Formulator::select('id', 'email', 'name', 'expertise', 'cv_file', 'cert_file', 'reg_no', 'membership_status', 'date_start', 'date_end')
                                ->whereDoesntHave('teamMember', function($q) use($id_project) {
                                    $q->whereHas('team', function($query) use($id_project) {
                                        $query->where('id_project', $id_project);
                                    });
                                })
                                ->whereHas('user')
                                ->where(function($q) {
                                    $q->where([['membership_status', '!=', 'TA'],['date_start', '<=', date('Y-m-d H:i:s')], ['date_end', '>=', date('Y-m-d H:i:s')]])->orWhere([['membership_status', '!=', 'TA'],['date_start', null], ['date_end', '>=', date('Y-m-d H:i:s')]]);
                                })
                                ->orderBy('name')
                                ->get();
        }

        if($request->type && $request->type == 'tenaga-ahli') {
            $id_project = $request->idProject;
            return Formulator::select('id', 'name', 'email', 'expertise', 'cv_file', 'cert_file', 'reg_no', 'membership_status', 'date_start', 'date_end')
                                ->whereHas('user')
                                ->where('membership_status', 'TA')
                                ->orderBy('name')
                                ->get();
        }

        if($request->type && $request->type == 'project') {
            $project = Project::findOrFail($request->idProject);
            $sk_letter = null;
            if(!$project->team_type == 'mandiri') {
                $team = FormulatorTeam::where('id_project', $project->id)->first();
                if($team) {
                    if($team->evidence_letter) {
                        $arr_exp = explode('/', $team->evidence_letter);
                        $sk_letter = $arr_exp[count($arr_exp) - 1];
                    }
                }
            }

            return [
                'name' => 'Tim Penyusun ' . $project->project_title,
                'type_team' => $project->type_formulator_team,
                'id_lpjp' => $project->id_lpjp,
                'sk_letter' => $sk_letter,
                'required_doc' => $project->required_doc
            ];
        }

        if($request->type && $request->type == 'lpjp') {
            $lpjp = Lpjp::where([['date_start', '<=', date('Y-m-d H:i:s')],['date_end', '>=', date('Y-m-d H:i:s')]])->get();
            return $lpjp;
        }

        if($request->type && $request->type == 'tim-penyusun') {
            $id_project = $request->idProject;
            $member = [];
            $pemrakarsa = $request->pemrakarsa;
            $formulatorTeamMember = FormulatorTeamMember::whereHas('team', function($q) use($id_project) {
                $q->where('id_project', $id_project);
            })->where([['id_formulator', '!=', null],['id_expert', null]])
            ->where(function($q) use($pemrakarsa) {
                if($pemrakarsa) {
                    $q->whereNotIn('position', ['Tenaga Ahli', 'Asisten']);
                }
            })->get();

            $num = 1;

            $project = Project::findOrFail($id_project);
            if($project->required_doc == 'AMDAL') {
                foreach($formulatorTeamMember as $f) {
                    $member[] = [
                        'num' => $num,
                        'id' => $f->id,
                        'db_id' => $f->id_formulator,
                        'name' => $f->formulator->name,
                        'value' => $f->formulator->name,
                        'type' => 'update',
                        'email' => $f->formulator->email,
                        'position' => $f->position,
                        'expertise' => $f->formulator->expertise,
                        'file' => $f->formulator->cv_file,
                        'certificate' => $f->formulator->cert_file,
                        'cert_no' => $f->formulator->cert_no,
                        'reg_no' => $f->formulator->reg_no,
                        'membership_status' => $f->formulator->membership_status
                    ];

                    $num++;
                }
            } else if($project->required_doc == 'UKL-UPL') {
                foreach($formulatorTeamMember as $f) {
                    $member[] = [
                        'name' => $f->formulator->name,
                        'id' => $f->id,
                        'id_formulator' => $f->id_formulator,
                        'email' => $f->formulator->email,
                        'expertise' => $f->formulator->expertise,
                        'cv_file' => $f->formulator->cv_file,
                        'reg_no' => $f->formulator->reg_no,
                        'membership_status' => $f->formulator->membership_status,
                        'cert_file' => $f->formulator->cert_file,
                        'num' => $num ?? null,
                        'db_id' => $f->id_formulator ?? null,
                        'value' => $f->formulator->name ?? null,
                        'type' => 'update',
                        'position' => $f->position ?? null,
                        'file' => $f->formulator->cv_file ?? null,
                        'certificate' => $f->formulator->cert_file ?? null,
                        'cert_no' => $f->formulator->cert_no ?? null,
                    ];

                    $num++;
                }
            }


            return $member;
        }

        if($request->type && $request->type == 'tim-ahli') {
            $id_project = $request->idProject;
            $member = [];
            $formulatorTeamMember = FormulatorTeamMember::whereHas('team', function($q) use($id_project) {
                $q->where('id_project', $id_project);
            })->where('id_formulator', '!=',  null)->whereIn('position', ['Tenaga Ahli','Asisten'])->get();

            $num = 1;
            foreach($formulatorTeamMember as $f) {
                $member[] = [
                    'num' => $num,
                    'id' => $f->id,
                    'name' => $f->formulator->name,
                    'email' => $f->formulator->email,
                    'id_formulator' => $f->id_formulator,
                    'type' => 'update',
                    'position' => $f->position,
                    'expertise' => $f->formulator->expertise,
                    'cv_file' => $f->formulator->cv_file
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
        if($request->type && $request->type == 'uklupl') {
            $receiver = [];
            DB::beginTransaction();
            try {
                $project = Project::findOrFail($request->idProject);
                $team = FormulatorTeam::where('id_project', $request->idProject)->first();
                if(!$team) {
                    $project->type_formulator_team = 'mandiri';
                    $project->save();

                    $team = new FormulatorTeam();
                    $team->id_team = uniqid();
                    $team->name = 'Tim Penyusun ' . $project->project_title;
                    $team->id_project = $request->idProject;
                    $team->save();
                }

                $members = $request->members;
                if(count($members) > 0) {
                    for($i = 0; $i < count($members); $i++) {
                        $team_member = new FormulatorTeamMember();
                        $team_member->id_formulator_team = $team->id;
                        $team_member->id_formulator = $members[$i];
                        $team_member->save();

                        $formulator = Formulator::findOrFail($members[$i]);
                        $user = User::where('email', $formulator->email)->first();
                        if($user) {
                            $receiver[] = $user;
                        }
                    }
                }

                $deleted = $request->deletedMembers;
                if(count($deleted) > 0) {
                    FormulatorTeamMember::whereIn('id', $deleted)->delete();
                }
            } catch(Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'code' => 500,
                    'message' => $e->getMessage(),
                ], 500);
            }

            DB::commit();

            // send notification
            if(count($receiver) > 0) {
                Notification::send($receiver, new FormulatorTeamAssigned($team));
            }

            return response()->json(['message' => 'success']);
        }

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
            } else {
                if($request->skFile) {
                    if($team->evidence_letter) {
                        Storage::disk('public')->delete($team->rawEvidenceLetter());
                    }
                }
            }

            if($request->skFile) {
                $fileSK = $this->base64ToFile($request->skFile);
                $skName = 'formulator-team/' . uniqid() . '.' . $fileSK['extension'];
                Storage::disk('public')->put($skName, $fileSK['file']);
                $team->evidence_letter = $skName;
            }

            $team->save();

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
            for ($i = 0; $i < count($membersAhli); $i++) {
                $teamMember = null;
                if($membersAhli[$i]['type'] == 'new') {
                    $teamMember = new FormulatorTeamMember();
                    $teamMember->id_formulator_team = $team->id;
                    $teamMember->id_formulator = $membersAhli[$i]['id_formulator'];
                } else {
                    $teamMember = FormulatorTeamMember::findOrFail($membersAhli[$i]['id']);
                }

                $teamMember->position = $membersAhli[$i]['position'];
                $teamMember->save();

                // add to list notification receiver
                $formulator = Formulator::find($membersAhli[$i]['id']);

                if($formulator){
                    $user = User::where('email', $formulator->email)->first();

                    if($user) {
                        array_push($receiver, $user);
                    }
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

    private function base64ToFile($file_64)
    {
        $extension = explode('/', explode(':', substr($file_64, 0, strpos($file_64, ';')))[1])[1];   // .jpg .png .pdf

        $replace = substr($file_64, 0, strpos($file_64, ',')+1);

        // find substring fro replace here eg: data:image/png;base64,

        $file = str_replace($replace, '', $file_64);

        $file = str_replace(' ', '+', $file);

        return [
            'extension' => $extension,
            'file' => base64_decode($file)
        ];
    }
}
