<?php

namespace App\Http\Controllers;

use App\Entity\Business;
use App\Entity\Formulator;
use App\Entity\FormulatorTeam;
use App\Entity\FormulatorTeamMember;
use App\Entity\Project;
use App\Entity\ProjectAddress;
use App\Entity\ProjectFilter;
use App\Entity\ProjectMapAttachment;
use App\Entity\SubProject;
use App\Entity\SubProjectParam;
use App\Http\Resources\ProjectResource;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->lastInput) {
            return Project::with('feasibilityTest')->whereDoesntHave('team')->orderBy('id', 'DESC')->first();
        } else if ($request->formulatorId) {
            //this code to get project base on formulator
            return Project::with(['address','listSubProject', 'feasibilityTest'])->select('projects.*', 'initiators.name as applicant', 'users.avatar as avatar', 'formulator_teams.id as team_id')->where(function ($query) use ($request) {
                return $request->document_type ? $query->where('result_risk', $request->document_type) : '';
            })->where(
                function ($query) use ($request) {
                    return $request->lpjpId ? $query->where('projects.id_lpjp', $request->lpjpId) : '';
                }
            )->where(
                function ($query) use ($request) {
                    return $request->formulatorId ? $query->where('formulators.id', $request->formulatorId) : '';
                }
            )->leftJoin('initiators', 'projects.id_applicant', '=', 'initiators.id')->leftJoin('users', 'initiators.email', '=', 'users.email')->leftJoin('formulator_teams', 'projects.id', '=', 'formulator_teams.id_project')->leftJoin('formulator_team_members', 'formulator_teams.id', '=', 'formulator_team_members.id_formulator_team')->leftJoin('formulators', 'formulators.id', '=', 'formulator_team_members.id_formulator')->orderBy('projects.id', 'DESC')->paginate($request->limit);
        }

        return Project::with(['address','listSubProject', 'feasibilityTest'])->select('projects.*', 'initiators.name as applicant', 'users.avatar as avatar', 'formulator_teams.id as team_id', 'announcements.id as announcementId')->where(function ($query) use ($request) {
            return $request->document_type ? $query->where('result_risk', $request->document_type) : '';
        })->where(
            function ($query) use ($request) {
                return $request->lpjpId ? $query->where('projects.id_lpjp', $request->lpjpId) : '';
            }
        )->where(
            function ($query) use ($request) {
                return $request->initiatorId ? $query->where('projects.id_applicant', $request->initiatorId) : '';
            }
        )->leftJoin('initiators', 'projects.id_applicant', '=', 'initiators.id')->leftJoin('users', 'initiators.email', '=', 'users.email')->leftJoin('formulator_teams', 'projects.id', '=', 'formulator_teams.id_project')->leftJoin('announcements', 'announcements.project_id', '=', 'projects.id')->orderBy('projects.id', 'DESC')->paginate($request->limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request['listSubProject'] = json_decode($request['listSubProject']);
        $request['address'] = json_decode($request['address']);
        if (isset($request['formulatorTeams'])) {
            $request['formulatorTeams'] = json_decode($request['formulatorTeams']);
        }
        $formulatorFiles = $request->file('formulatorFiles');

        // return $formulatorFiles[2];

        // return $request['listSubProject'];

        //validate request
        $data = $this->validate($request, [
            'project_title' => 'required',
            'project_type' => 'required',
            'sector' => 'required',
            'description' => 'required',
            'id_applicant' => 'required',
            // 'id_prov' => 'required',
            // 'id_district' => 'required',
            'address' => 'required',
            // 'field' => 'required',
            'location_desc' => 'required',
            'risk_level' => 'required',
            'kbli' => 'required',
            'result_risk' => 'required',
            'required_doc' => 'required',
            'type_formulator_team' => 'required',
            'listSubProject' => 'required',
            'scale' => 'required',
            'scale_unit' => 'required',
        ]);

        // return $data;

        //create fileKtr
        $preAgreementName = '';
        if ($request->file('filePreAgreement')) {
            $filePreAgreement = $request->file('filePreAgreement');
            $preAgreementName = 'project/preAgreement/' . uniqid() . '.' . $filePreAgreement->extension();
            $filePreAgreement->storePubliclyAs('public', $preAgreementName);
        }

        //create fileKtr
        $ktrName = '';
        if ($request->file('fileKtr')) {
            $fileKtr = $request->file('fileKtr');
            $ktrName = 'project/ktr' . uniqid() . '.' . $fileKtr->extension();
            $fileKtr->storePubliclyAs('public', $ktrName);
        }

        //create file map

        DB::beginTransaction();

        try {
            //create project
            $project = Project::create([
                // 'biz_type' => $data['biz_type'],
                'project_title' => $data['project_title'],
                'scale' => $data['scale'],
                'scale_unit' => $data['scale_unit'],
                'project_type' => $data['project_type'],
                'sector' => $data['sector'],
                'description' => $data['description'],
                'id_applicant' => $data['id_applicant'],
                // 'id_prov' => $data['id_prov'],
                // 'id_district' => $data['id_district'],
                // 'address' => $data['address'],
                'field' => isset($request['field']) ? $request['field'] : null,
                'location_desc' => $data['location_desc'],
                'risk_level' => $data['risk_level'],
                'project_year' => date('Y'),
                'id_formulator_team' => isset($request['id_formulator_team']) ? $request['id_formulator_team'] : null,
                'kbli' => $data['kbli'],
                'result_risk' => $data['result_risk'],
                'required_doc' => $data['required_doc'],
                // 'id_project' => $data['id_project'],
                'type_formulator_team' => $data['type_formulator_team'],
                'id_lpjp' => isset($request['id_lpjp']) ? $request['id_lpjp'] : null,
                'map' => '',
                'ktr' => Storage::url($ktrName),
                'pre_agreement' => isset($request['pre_agreement']) ? $request['pre_agreement'] : null,
                'pre_agreement_file' => Storage::url($preAgreementName),
                'registration_no' => uniqid(),
            ]);

            if ($files = $request->file('fileMap')) {
                $mapName = time() . '_' . $project->id . '_' . uniqid('projectmap') . '.zip';
                $files->storePubliclyAs('public/map/', $mapName);
                ProjectMapAttachment::create([
                    'id_project' => $project->id,
                    'attachment_type' => 'tapak',
                    'file_type' => 'SHP',
                    'original_filename' => 'Peta Tapak',
                    'stored_filename' => $mapName
                ]);
            }

            //if mandiri create tim mandiri
            if (isset($request['formulatorTeams'])) {
                //create team
                $team = FormulatorTeam::create([
                    'id_project' => $project->id,
                    'id_team' => uniqid(),
                    'name' => 'Tim ' . $project->project_title,
                ]);

                foreach ($request['formulatorTeams'] as $key => $formulaTeam) {

                    if (!isset($formulaTeam->id)) {
                        $cvName = '';
                        if (array_key_exists($key, $formulatorFiles)) {
                            $cvName = '/penyusun/' . uniqid() . '.' . $formulatorFiles[$key]->extension();
                            $formulatorFiles[$key]->storePubliclyAs('public', $cvName);
                        }

                        $email = $formulaTeam->email;
                        $found = User::where('email', $email)->first();
                        if (!$found) {
                            $formulatorRole = Role::findByName(Acl::ROLE_FORMULATOR);
                            $user = User::create([
                                'name' => ucfirst($formulaTeam->name),
                                'email' => $formulaTeam->email,
                                'password' => Hash::make('amdalnet')
                            ]);
                            $user->syncRoles($formulatorRole);
                        }

                        $new = Formulator::create([
                            'name' => $formulaTeam->name,
                            'cv_file' => Storage::url($cvName),
                            'email' => $formulaTeam->email,
                            'expertise' => $formulaTeam->expertise,
                            'membership_status' => 'TA',
                        ]);

                        $formulaTeam->id = $new->id;
                    }

                    FormulatorTeamMember::create([
                        'id_formulator_team' => $team->id,
                        'position' => ucfirst($formulaTeam->position),
                        'id_formulator' => $formulaTeam->id,
                    ]);
                }
            }

            //set project filters
            ProjectFilter::create([
                'id_project' => $project->id,
                'wastewater' => isset($request['wastewater']) ? $request['wastewater'] : false,
                'disposal_wastewater' => isset($request['disposal_wastewater']) ? $request['disposal_wastewater'] : false,
                'utilization_wastewater' => isset($request['utilization_wastewater']) ? $request['utilization_wastewater'] : false,
                'high_pollution' => isset($request['high_pollution']) ? $request['high_pollution'] : false,
                'emission' => isset($request['emission']) ? $request['emission'] : false,
                'chimney' => isset($request['chimney']) ? $request['chimney'] : false,
                'genset' => isset($request['genset']) ? $request['genset'] : false,
                'high_emission' => isset($request['high_emission']) ? $request['high_emission'] : false,
                'b3' => isset($request['b3']) ? $request['b3'] : false,
                'collect_b3' => isset($request['collect_b3']) ? $request['collect_b3'] : false,
                'hoard_b3' => isset($request['hoard_b3']) ? $request['hoard_b3'] : false,
                'process_b3' => isset($request['process_b3']) ? $request['process_b3'] : false,
                'utilization_b3' => isset($request['utilization_b3']) ? $request['utilization_b3'] : false,
                'dumping_b3' => isset($request['dumping_b3']) ? $request['dumping_b3'] : false,
                'tps' => isset($request['tps']) ? $request['tps'] : false,
                'traffic' => isset($request['traffic']) ? $request['traffic'] : false,
                'low_traffic' => isset($request['low_traffic']) ? $request['low_traffic'] : false,
                'mid_traffic' => isset($request['mid_traffic']) ? $request['mid_traffic'] : false,
                'high_traffic' => isset($request['high_traffic']) ? $request['high_traffic'] : false,
            ]);

            //create project address
            foreach ($data['address'] as $add) {
                ProjectAddress::create([
                    'id_project' => $project->id,
                    'prov' => isset($add->prov) ? $add->prov : null,
                    'district' => isset($add->district) ? $add->district : null,
                    'address' => isset($add->address) ? $add->address : null,
                ]);
            }

            //create sub project
            foreach ($data['listSubProject'] as $subPro) {
                $business = Business::find($subPro->biz_type);
                $sector = Business::find($subPro->sector);
                $createdSubPro = SubProject::create([
                    'kbli' => isset($subPro->kbli) ? $subPro->kbli : 'Non KBLI',
                    'name' => $subPro->name,
                    'result' => $subPro->result,
                    'type' => $subPro->type,
                    'biz_type' => $subPro->biz_type,
                    'id_project' => $project->id,
                    'sector' => $subPro->sector,
                    'scale' => $subPro->scale,
                    'scale_unit' => isset($subPro->scale_unit) ? $subPro->scale_unit : '',
                    'biz_name' => isset($business) ? $business->value : null,
                    'sector_name' => isset($sector) ? $sector->value : null,
                ]);

                foreach ($subPro->listSubProjectParams as $subProParam) {
                    SubProjectParam::create([
                        'param' => $subProParam->param,
                        'scale' => $subProParam->scale,
                        'scale_unit' => isset($subProParam->scale_unit) ? $subProParam->scale_unit : '',
                        'result' => $subProParam->result,
                        'id_sub_project' => $createdSubPro->id,
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw new Exception('Gagal Membuat Kegiatan karena '.$e);
        }

        return new ProjectResource($project);

        // $validator = Validator::make(
        //     $request->all(),
        //     [
        //         'biz_type' => 'required',
        //         'project_title' => 'required',
        //         'scale' => 'required',
        //         'scale_unit' => 'required',
        //         'project_type' => 'required',
        //         'sector' => 'required',
        //         'description' => 'required',
        //         'id_applicant' => 'required',
        //         'id_prov' => 'required',
        //         'id_district' => 'required',
        //         'address' => 'required',
        //         'field' => 'required',
        //         'location_desc' => 'required',
        //         'risk_level' => 'required',
        //         'project_year' => 'required',
        //         // 'id_formulator_team' => 'required',
        //         'kbli' => 'required',
        //         'result_risk' => 'required',
        //         'required_doc' => 'required',
        //         'type_formulator_team' => 'required',
        //     ]
        // );

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 403);
        // } else {
        //     $params = $request->all();

        //     //create fileKtr
        //     $ktrName = '';
        //     if ($request->file('fileKtr')) {
        //         $fileKtr = $request->file('fileKtr');
        //         $ktrName = 'project/ktr' . uniqid() . '.' . $fileKtr->extension();
        //         $fileKtr->storePubliclyAs('public', $ktrName);
        //     }

        //     //create file map
        //     $mapName = array();
        //     if ($files = $request->file('fileMap')) {
        //         foreach ($files as $file) {
        //             $name = uniqid() . '.zip';
        //             $mapName[] = $name;
        //             $file->move(storage_path('app/public/map'), $name);
        //         }
        //     }

        //     //create lpjp
        //     $project = Project::create([
        //         'biz_type' => $params['biz_type'],
        //         'project_title' => $params['project_title'],
        //         'scale' => $params['scale'],
        //         'scale_unit' => $params['scale_unit'],
        //         'project_type' => $params['project_type'],
        //         'sector' => $params['sector'],
        //         'description' => $params['description'],
        //         'id_applicant' => $params['id_applicant'],
        //         'id_prov' => $params['id_prov'],
        //         'id_district' => $params['id_district'],
        //         'address' => $params['address'],
        //         'field' => $params['field'],
        //         'location_desc' => $params['location_desc'],
        //         'risk_level' => $params['risk_level'],
        //         'project_year' => $params['project_year'],
        //         'id_formulator_team' => isset($params['id_formulator_team']) ? $params['id_formulator_team'] : null,
        //         'kbli' => $params['kbli'],
        //         'result_risk' => $params['result_risk'],
        //         'required_doc' => $params['required_doc'],
        //         'id_project' => $params['id_project'],
        //         'type_formulator_team' => $params['type_formulator_team'],
        //         'id_lpjp' => isset($params['id_lpjp']) ? $params['id_lpjp'] : null,
        //         'map' => implode(',', $mapName),
        //         'ktr' => Storage::url($ktrName),
        //     ]);

        //     return new ProjectResource($project);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project)
    {
        return $project;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @return Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function update(Request $request, Project $project)
    {

        if ($project === null) {
            return response()->json(['error' => 'project not found'], 404);
        }

        //validate request

        $validator = Validator::make(
            $request->all(),
            [
                'biz_type' => 'required',
                'project_title' => 'required',
                'scale' => 'required',
                'scale_unit' => 'required',
                'project_type' => 'required',
                'sector' => 'required',
                'description' => 'required',
                'id_applicant' => 'required',
                // 'id_prov' => 'required',
                // 'id_district' => 'required',
                // 'address' => 'required',
                'field' => 'required',
                'location_desc' => 'required',
                'risk_level' => 'required',
                'project_year' => 'required',
                // 'id_formulator_team' => 'required',
                'kbli' => 'required',
                'result_risk' => 'required',
                'required_doc' => 'required',
                'type_formulator_team' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create file map
            if ($request->file('fileMap')) {
                $file = $request->file('fileMap');
                $name = 'project/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
            } else {
                $name = null;
            }

            //create file ktr
            if ($request->file('fileKtr')) {
                $fileKtr = $request->file('fileKtr');
                $ktrName = 'project/ktr' . uniqid() . '.' . $fileKtr->extension();
                $fileKtr->storePubliclyAs('public', $ktrName);
            } else {
                $ktrName = null;
            }

            //create Pre Agreement
            $preAgreementName = '';
            if ($request->file('fileKtr')) {
                $filePreAgreement = $request->file('fileKtr');
                $preAgreementName = 'project/preAgreement' . uniqid() . '.' . $filePreAgreement->extension();
                $filePreAgreement->storePubliclyAs('public', $preAgreementName);
            }

            //Update Project
            $project->biz_type = $params['biz_type'];
            $project->project_title = $params['project_title'];
            $project->scale = $params['scale'];
            $project->scale_unit = $params['scale_unit'];
            $project->project_type = $params['project_type'];
            $project->sector = $params['sector'];
            $project->description = $params['description'];
            $project->id_applicant = $params['id_applicant'];
            // $project->id_prov = $params['id_prov'];
            // $project->id_district = $params['id_district'];
            // $project->address = $params['address'];
            $project->field = $params['field'];
            $project->location_desc = $params['location_desc'];
            $project->risk_level = $params['risk_level'];
            $project->project_year = $params['project_year'];
            $project->id_formulator_team = isset($params['id_formulator_team']) ? $params['id_formulator_team'] : null;
            $project->kbli = $params['kbli'];
            $project->result_risk = $params['result_risk'];
            $project->required_doc = $params['required_doc'];
            $project->id_project = $params['id_project'];
            $project->type_formulator_team = $params['type_formulator_team'];
            $project->id_lpjp = isset($params['id_lpjp']) ? $params['id_lpjp'] : null;
            $project->map = $name != null ? Storage::url($name) : $project->map;
            $project->ktr = $ktrName != null ? Storage::url($ktrName) : $project->ktr;
            $project->pre_agreement = $preAgreementName != null ? Storage::url($preAgreementName) : $project->pre_agreement;

            $project->save();
        }

        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
        } catch (Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
