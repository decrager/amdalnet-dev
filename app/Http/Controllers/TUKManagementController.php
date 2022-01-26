<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBank;
use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\LukMember;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TUKManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->type == 'profileMember') {
            $members = FeasibilityTestTeamMember::where('id_feasibility_test_team', 6)->get();
            $member = [];

            foreach($members as $m) {
                $cv = null;

                if($m->lukMember) {
                    $cv = $m->lukMember->cv;
                } else if($m->expertBank) {
                    $cv = $m->expertBank->cv_file;
                }

                $member[] = [
                    'id' => $m->id,
                    'name' => $m->lukMember ? $m->lukMember->name : $m->expertBank->name,
                    'nik' => $m->lukMember ? $m->lukMember->nik : '-',
                    'position' => $m->position . ' Tim Uji Kelayakan' ,
                    'institution' => $m->lukMember ? $m->lukMember->institution : $m->expertBank->institution,
                    'cv' => $cv
                ];
            }

            return $member;
        }

        if($request->type == 'profile') {
            $team = FeasibilityTestTeam::find(6);
            $team_name = null;
            if($team->authority == 'Pusat') {
                $team_name = 'Tim Uji Kelayakan Pusat ' . $team->team_number;
            } else if($team->authority == 'Provinsi' && $team->provinceAuthority) {
                $team_name = 'Tim Uji kelayakan Provinsi ' . ucwords(strtolower($team->provinceAuthority->name)) . ' ' . $team->team_number;
            } else if($team->authority == 'Kabupaten/Kota' && $team->districtAuthority) {
                $team_name = 'Tim Uji kelayakan ' . ucwords(strtolower($team->districtAuthority->name)) . ' ' . $team->team_number;
            }

            return [
                'id' => $team->id,
                'name' => $team_name,
                'email' => $team->email,
                'phone' => $team->phone,
                'address' => $team->address,
                'logo' => $team->logo
            ];
        }

        if($request->type == 'listSelect') {
            $tuks = [];
            $teams = FeasibilityTestTeam::all();

            foreach($teams as $tuk) {
                $team_name = null;
                if($tuk->authority == 'Pusat') {
                    $team_name = 'Tim Uji Kelayakan Pusat ' . $tuk->team_number;
                } else if($tuk->authority == 'Provinsi' && $tuk->provinceAuthority) {
                    $team_name = 'Tim Uji kelayakan Provinsi ' . ucwords(strtolower($tuk->provinceAuthority->name)) . ' ' . $tuk->team_number;
                } else if($tuk->authority == 'Kabupaten/Kota' && $tuk->districtAuthority) {
                    $team_name = 'Tim Uji kelayakan ' . ucwords(strtolower($tuk->districtAuthority->name)) . ' ' . $tuk->team_number;
                }

                $tuks[] = [
                    'id' => $tuk->id,
                    'name' => $team_name
                ];
            }

            return $tuks;
        }

        if($request->type == 'members') {
            $members = FeasibilityTestTeamMember::where('id_feasibility_test_team', $request->id)->get();
            $team_members = [];

            $num = 0;
            foreach($members as $m) {
                $nik = '-';
                $institution = null;
                $cv = null;
                $position = '-';
                $email = '';

                if($m->lukMember) {
                    $nik = $m->lukMember->nik;
                    $institution = $m->lukMember->institution;
                    $cv = $m->lukMember->cv;
                    $email = $m->lukMember->email;
                    $position = $m->lukMember->position;
                } else if($m->expertBank) {
                    $institution = $m->expertBank->institution;
                    $cv = $m->expertBank->cv_file;
                    $email = $m->expertBank->email;
                }

                $team_members[] = [
                    'num' => $num,
                    'id' => $m->id,
                    'id_member' => $m->id_expert_bank ? $m->id_expert_bank . '-' . 'expert' : $m->id_luk_member . '-' . 'employee',
                    'idTeam' => $request->id,
                    'type' => $m->id_expert_bank != null ? 'expert' : 'employee',
                    'nik' => $nik,
                    'role' => $m->position,
                    'old_role' => $m->position,
                    'position' => $position,
                    'institution' => $institution,
                    'cv' => $cv,
                    'email' => $email
                ];

                $num++;
            }

            return $team_members;
        }

        if($request->type == 'expertEmployee') {
            $expertEmployee = [];

            $experts = ExpertBank::select('id', 'name', 'institution', 'cv_file', 'email')->orderBy('name')->get();
            $employees = LukMember::select('id', 'nik', 'name', 'institution', 'position', 'cv', 'email')->whereDoesntHave('feasibilityTestTeamMember')->orderBy('name')->get();

            $existing_employees = FeasibilityTestTeamMember::where([['id_feasibility_test_team', $request->idTUK],['id_luk_member', '!=', null]])->get();
            
            foreach($experts as $ex) {
                $expertEmployee[] = [
                    'id' => $ex->id,
                    'type' => 'expert',
                    'name' => $ex->name,
                    'nik' => '-',
                    'position' => '-',
                    'institution' => $ex->institution,
                    'cv' => $ex->cv_file,
                    'email' => $ex->email
                ];
            }

            foreach($employees as $em) {
                $expertEmployee[] = [
                    'id' => $em->id,
                    'type' => 'employee',
                    'name' => $em->name,
                    'nik' => $em->nik,
                    'position' => $em->position,
                    'institution' => $em->institution,
                    'cv' => $em->cv,
                    'email' => $em->email
                ];
            }

            foreach($existing_employees as $exe) {
                if($exe->lukMember) {
                    $expertEmployee[] = [
                        'id' => $exe->lukMember->id,
                        'type' => 'employee',
                        'name' => $exe->lukMember->name,
                        'nik' => $exe->lukMember->nik,
                        'position' => $exe->lukMember->position,
                        'institution' => $exe->lukMember->institution,
                        'cv' => $exe->lukMember->cv,
                        'email' => $exe->lukMember->email
                    ];
                }
            }

            return $expertEmployee;
        }

        if($request->type == 'teamDataMember') {
            $tuk = FeasibilityTestTeam::find($request->idTeam);
            
            $team_name = null;
            if($tuk->authority == 'Pusat') {
                $team_name = 'Tim Uji Kelayakan Pusat ' . $tuk->team_number;
            } else if($tuk->authority == 'Provinsi' && $tuk->provinceAuthority) {
                $team_name = 'Tim Uji kelayakan Provinsi ' . ucwords(strtolower($tuk->provinceAuthority->name)) . ' ' . $tuk->team_number;
            } else if($tuk->authority == 'Kabupaten/Kota' && $tuk->districtAuthority) {
                $team_name = 'Tim Uji kelayakan ' . ucwords(strtolower($tuk->districtAuthority->name)) . ' ' . $tuk->team_number;
            }
            return [
                'authority' => $tuk->authority,
                'team_name' => $team_name
            ];
        }

        if($request->type == 'edit') {
            $tuk = FeasibilityTestTeam::find($request->idTeam);
            return $tuk;
        }

        if($request->type == 'list') {
            $teams = FeasibilityTestTeam::with(['member' => function($q) {
                $q->where('position', 'Ketua')->with(['expertBank', 'lukMember']);
            }, 'provinceAuthority', 'districtAuthority'])->orderBy('id', 'desc')->paginate($request->limit);
            return $teams;
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
        if($request->profile) {
            $data = $request->all();
            $validator = \Validator::make($data, [
                'email' => 'required|email',
                'phone' => 'required',
                'address' => 'required',
                'logo' => 'max:1024'
            ],[
                'email.required' => 'Email Wajib Diisi',
                'email.email' => 'Email Tidak Valid',
                'phone.required' => 'No. Telepon Wajib Diisi',
                'address.required' => 'Alamat Wajib Diisi',
                'logo.max' => 'Ukuran file tidak boleh melebihi 1 MB',
            ]);

            if($validator->fails()) {
                return response()->json(['errors' => $validator->messages()]);
            }

            $tuk = FeasibilityTestTeam::findOrFail(6);
            $tuk->email = $data['email'];
            $tuk->phone = $data['phone'];
            $tuk->address = $data['address'];

            if($request->hasFile('logo')) {
                $validator = \Validator::make($data, [
                    'logo' => 'image'
                ],[
                    'logo.image' => 'Logo Tidak Valid'
                ]);

                if($validator->fails()) {
                    return response()->json(['errors' => $validator->messages()]);
                }

                $file = $request->file('logo');
                $name = '/logo_tuk/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $tuk->logo = Storage::url($name);
            }

            $tuk->save();

            return response()->json(['errors' => null, 'message' => 'success']);


        }

        if($request->member) {
            $members = $request->members;
            $deleted = $request->deleted;

            for($i = 0; $i < count($members); $i++) {
                $member = null;

                if($members[$i]['id']) {
                    $member = FeasibilityTestTeamMember::findOrFail($members[$i]['id']);
                } else {
                    $member = new FeasibilityTestTeamMember();
                }
    
                $member->id_feasibility_test_team = $members[$i]['idTeam'];
                $member->position = $members[$i]['role'];
                
                if($members[$i]['id_member']) {
                    if($members[$i]['type'] == 'expert') {
                        $member->id_expert_bank = explode('-', $members[$i]['id_member'])[0];
                        $member->id_luk_member = null;
                    } else if($members[$i]['type'] == 'employee') {
                        $member->id_luk_member = explode('-', $members[$i]['id_member'])[0];
                        $member->id_expert_bank = null;
                    }
                }
    
                $member->save();

                // ====== CHANGE USER ROLE ======= //
            
                if($members[$i]['id']) {
                    if($members[$i]['role'] == 'Kepala Sekretariat') {
                        if($members[$i]['old_role'] != 'Kepala Sekretariat') {
                            $user = User::where('email', $members[$i]['email'])->count();
                            if($user > 0) {
                                $user = User::where('email', $members[$i]['email'])->first();
                                $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SECRETARY);
                                $user->syncRoles($valsubRole);
                            }
                        }
                    } else {
                        if($members[$i]['old_role'] == 'Kepala Sekretariat') {
                            $user = User::where('email', $members[$i]['email'])->count();
                            if($user > 0) {
                                $user = User::where('email', $members[$i]['email'])->first();
                                $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SUBSTANCE);
                                $user->syncRoles($valsubRole);
                            }
                        }
                    }
                } else {
                    if($members[$i]['role'] == 'Kepala Sekretariat') {
                        $user = User::where('email', $members[$i]['email'])->count();
                        if($user > 0) {
                            $user = User::where('email', $members[$i]['email'])->first();
                            $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SECRETARY);
                            $user->syncRoles($valsubRole);
                        }
                    }
                }
            }

            for($a = 0; $a < count($deleted); $a++) {
                FeasibilityTestTeamMember::destroy($deleted[$a]['id']);
                if($deleted[$a]['role'] == 'Kepala Sekretariat') {
                    if($deleted[$a]['email']) {
                        $user = User::where('email', $deleted[$a]['email'])->count();
                        if($user > 0) {
                            $user = User::where('email', $deleted[$a]['email'])->first();
                            $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SUBSTANCE);
                            $user->syncRoles($valsubRole);
                        }
                    }
                }
            }

            return response()->json(['message' => 'success']);
        }

        $input = $request->all();

        $validator = \Validator::make($input,[
            'authority' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ],[
            'authority.required' => 'Kewenangan Wajib Dipilih',
            'email.required' => 'Email Wajib Diisi',
            'phone.required' => 'Nomor Kontak Wajib Diisi',
            'address.required' => 'Alamat Wajib Dipilih',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->messages()]);
        }

        $tuk = null;

        if($request->type == 'create') {
            $tuk = new FeasibilityTestTeam();
        } else {
            $tuk = FeasibilityTestTeam::findOrFail($request->idTeam);
        }

        $tuk->authority = $request->authority;
        $tuk->assignment_number = $request->assignment_number;
        $tuk->phone = $request->phone;
        $tuk->email = $request->email;
        $tuk->address = $request->address;
        $tuk->id_province_name = $request->id_province_name;
        $tuk->id_district_name = $request->id_district_name;

        if($request->team_number == 0 || $request->team_number == null) {
            $tuk->team_number = null;
        } else {
            $tuk->team_number = $request->team_number;
        }

        if($request->hasFile('assignment_file')) {
            $file = $request->file('assignment_file');
            $name = '/assignment_letter/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            $tuk->assignment_file = Storage::url($name);
        }

        $tuk->save();

        return response()->json(['errors' => null, 'message' => 'Data successfully saved']);
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
        FeasibilityTestTeam::destroy($id);
        return response()->json(['message' => 'Data successfully deleted']);
    }
}
