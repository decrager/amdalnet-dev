<?php

namespace App\Http\Controllers;

use App\Entity\Formulator;
use App\Entity\Lpjp;
use App\Entity\Project;
use App\Http\Resources\LpjpResource;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LpjpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->formulators) {
            $formulators = Formulator::where('id_lpjp', $request->idLpjp)->orWhere('id_lpjp', null)->orderBy('name')->get();
            return $formulators;
        }

        if($request->member) {
            $lpjp = Lpjp::select('id', 'name')->whereKey($request->idLpjp)->first();
            $members = Formulator::where('id_lpjp', $request->idLpjp)->get();
            $formulators = [];

            $num = 1;
            foreach($members as $m) {
                $status = 'Tidak Aktif';

                if($m->date_end) {
                    if($m->date_end >= date('Y-m-d H:i:s')) {
                        $status = 'Aktif';
                    }
                }

                $formulators[] = [
                    'num' => $num,
                    'id' => $m->id,
                    'name' => $m->name,
                    'reg_no' => $m->reg_no,
                    'cert_no' => $m->cert_no,
                    'membership_status' => $m->membership_status,
                    'status' => $status,
                    'cert_file' => $m->cert_file,
                    'cv_file' => $m->file,
                    'type' => 'update'
                ];
                $num++;
            }

            return response()->json([
                'lpjp' => $lpjp,
                'members' => $formulators
            ]);
            
        }

        return LpjpResource::collection(Lpjp::where(function ($query) use ($request) {
            if ($request->active) {
                return $query->where([['date_start', '<=', date('Y-m-d H:i:s')], ['date_end', '>=', date('Y-m-d H:i:s')]])
                    ->orWhere([['date_start', null], ['date_end', '>=', date('Y-m-d H:i:s')]]);
            }
        })->with(['province' => function ($query) {
            return $query->select(['id', 'name']);
        }, 'district' => function ($query) {
            return $query->select(['id', 'name']);
        }])->get());
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
        if($request->member) {
            $members = $request->members;
            $deleted_members = $request->deletedMembers;
            $id_lpjp = $request->idLpjp;

            if(count($members) > 0) {
                Formulator::whereIn('id', $members)->update(['id_lpjp' => $id_lpjp]);
            }

            if(count($deleted_members) > 0) {
                Formulator::whereIn('id', $deleted_members)->update(['id_lpjp' => null]);
            }

            return response()->json(['message' => 'success']);
        }

        //validate request

        $validator = Validator::make(
            $request->all(),
            [
                'name'              => 'required',
                'pic'               => 'required',
                'reg_no'            => 'required',
                'address'           => 'required',
                'id_prov'           => 'required',
                'id_district'       => 'required',
                'mobile_phone_no'   => 'required',
                'email'             => 'required',
                'file'              => 'required',
                'contact_person'    => 'required',
                'phone_no'          => 'required',
                'url_address'       => 'required',
                'date_start'        => 'required',
                'date_end'          => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            DB::beginTransaction();

            //create file
            $file = $request->file('file');
            $name = '/lpjp/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            $email = $request->get('email');
            $found = User::where('email', $email)->first();
            if (!$found) {
                $lpjpRole = Role::findByName(Acl::ROLE_LPJP);
                $user = User::create([
                    'name' => ucfirst($params['name']),
                    'email' => $params['email'],
                    'password' => Hash::make('amdalnet')
                ]);
                $user->syncRoles($lpjpRole);
            }

            //create lpjp
            $lpjp = Lpjp::create([
                'name' => $params['name'],
                'pic' => $params['pic'],
                'reg_no' => $params['reg_no'],
                'address' => $params['address'],
                'id_prov' => $params['id_prov'],
                'id_district' => $params['id_district'],
                'mobile_phone_no' => $params['mobile_phone_no'],
                'email' => $params['email'],
                'cert_file' => Storage::url($name),
                'contact_person' => $params['contact_person'],
                'phone_no' => $params['phone_no'],
                'url_address' => $params['url_address'],
                'date_start' => $params['date_start'],
                'date_end' => $params['date_end'],
            ]);

            if (!$lpjp) {
                DB::rollback();
            } else {
                DB::commit();
            }

            return new LpjpResource($lpjp);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\Lpjp  $lpjp
     * @return \Illuminate\Http\Response
     */
    public function show(Lpjp $lpjp)
    {
        //
    }

    public function showByEmail(Request $request)
    {
        if ($request->email) {
            $lpjp = Lpjp::where('email', $request->email)->first();

            if ($lpjp) {
                return $lpjp;
            } else {
                return response()->json(null, 200);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Lpjp  $lpjp
     * @return \Illuminate\Http\Response
     */
    public function edit(Lpjp $lpjp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Lpjp  $lpjp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lpjp $lpjp)
    {
        if ($lpjp === null) {
            return response()->json(['error' => 'lpjp not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name'              => 'required',
                'pic'               => 'required',
                'reg_no'            => 'required',
                'address'           => 'required',
                'id_prov'           => 'required',
                'id_district'       => 'required',
                'mobile_phone_no'   => 'required',
                'email'             => 'required',
                'contact_person'    => 'required',
                'phone_no'          => 'required',
                'url_address'       => 'required',
                'date_start'        => 'required',
                'date_end'          => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            if ($request->file('file') !== null) {
                //create file
                $file = $request->file('file');
                $name = '/lpjp/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $lpjp->cert_file = Storage::url($name);
            }
            $lpjp->name = $params['name'];
            $lpjp->pic = $params['pic'];
            $lpjp->reg_no = $params['reg_no'];
            $lpjp->address = $params['address'];
            $lpjp->id_prov = $params['id_prov'];
            $lpjp->id_district = $params['id_district'];
            $lpjp->mobile_phone_no = $params['mobile_phone_no'];
            $lpjp->email = $params['email'];
            $lpjp->contact_person = $params['contact_person'];
            $lpjp->phone_no = $params['phone_no'];
            $lpjp->url_address = $params['url_address'];
            $lpjp->date_start = $params['date_start'];
            $lpjp->date_end = $params['date_end'];
            $lpjp->save();
        }

        return new LpjpResource($lpjp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Lpjp  $lpjp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lpjp $lpjp)
    {
        try {
            $lpjp->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    public function getFormulator(Request $request)
    {
        $getData = DB::table('projects')
            ->select('lpjp.name as lpjp_name', 'projects.project_title', 'formulator_teams.name as formulator_name', 'projects.required_doc', 'projects.created_at')
            ->leftJoin('lpjp', 'lpjp.id', '=', 'projects.id_lpjp')
            ->leftJoin('formulator_teams', 'formulator_teams.id_project', '=', 'projects.id')
            ->where('projects.id', '=', $request->id)
            ->get();

        $jmlAnggotaNonTa = DB::table('projects')
            ->select('formulators.name as formulator_name', 'formulators.reg_no', 'formulators.membership_status', 'formulator_team_members.position', 'formulators.expertise', 'formulators.cv_file')
            ->leftJoin('lpjp', 'lpjp.id', '=', 'projects.id_lpjp')
            ->leftJoin('formulator_teams', 'formulator_teams.id_project', '=', 'projects.id')
            ->leftJoin('formulator_team_members', 'formulator_team_members.id_formulator_team', '=', 'formulator_teams.id')
            ->leftJoin('formulators', 'formulators.id', '=', 'formulator_team_members.id_formulator')
            ->where('projects.id', '=', $request->id)
            ->where('formulators.membership_status', '!=', 'TA')
            ->get();

        $jmlAnggotaTa = DB::table('projects')
            ->select('formulators.name as formulator_name', 'formulator_team_members.position', 'formulators.expertise', 'formulators.cv_file')
            ->leftJoin('lpjp', 'lpjp.id', '=', 'projects.id_lpjp')
            ->leftJoin('formulator_teams', 'formulator_teams.id_project', '=', 'projects.id')
            ->leftJoin('formulator_team_members', 'formulator_team_members.id_formulator_team', '=', 'formulator_teams.id')
            ->leftJoin('formulators', 'formulators.id', '=', 'formulator_team_members.id_formulator')
            ->where('projects.id', '=', $request->id)
            ->where('formulators.membership_status', '=', 'TA')
            ->get();
        return response()->json([
            'data' => $getData,
            'jumlah' => $jmlAnggotaNonTa,
            'ta' => $jmlAnggotaTa
        ]);
    }
}
