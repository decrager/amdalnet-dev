<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBank;
use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\LukMember;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use App\Notifications\TUKAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
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
            $id_team = $this->getIdTeamByMemberEmail($request->email);
            if($id_team == null) {
                return [];
            }

            $members = FeasibilityTestTeamMember::where('id_feasibility_test_team', $id_team)->get();
            $member = [];

            foreach($members as $m) {
                $cv = null;
                $email = null;
                $role = null;
                $type = null;

                if($m->lukMember) {
                    $cv = $m->lukMember->cv;
                    $email = $m->lukMember->email;
                    $type = 'luk_member';
                } else if($m->expertBank) {
                    $cv = $m->expertBank->cv_file;
                    $email = $m->expertBank->email;
                    $type = 'expert_bank';
                }

                $user = User::where('email', $email)->with('roles')->first();
                if($user) {
                    $role = $user->roles->first()->name;
                }

                $member[] = [
                    'id' => $m->id,
                    'name' => $m->lukMember ? $m->lukMember->name : $m->expertBank->name,
                    'nik' => $m->lukMember ? $m->lukMember->nik : '-',
                    'position' => $m->position . ' Tim Uji Kelayakan' ,
                    'institution' => $m->lukMember ? $m->lukMember->institution : $m->expertBank->institution,
                    'cv' => $cv,
                    'role' => $role,
                    'type' => $type
                ];
            }

            return $member;
        }

        if($request->type == 'profile') {
            $id_team = $this->getIdTeamByMemberEmail($request->email);
            $team = FeasibilityTestTeam::find($id_team);

            if($team) {
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
            } else {
                return [
                    'id' => null,
                    'name' => null,
                    'email' => null,
                    'phone' => null,
                    'address' => null,
                    'logo' => null
                ];
            }
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

            $tuk = FeasibilityTestTeam::findOrFail($request->idTeam);
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

            // === MEMBERS === //
            $members = json_decode($request->members, true);
            if(count($members) > 0) {
                for($i = 0; $i < count($members); $i++) {
                    $team_member = FeasibilityTestTeamMember::findOrFail($members[$i]['id']);
                    $email = $team_member->lukMember->email;
                    $user = User::where('email', $email)->count();
                    if($user > 0) {
                        $user = User::where('email', $email)->first();
                        if($members[$i]['role'] == 'examiner-substance') {
                            $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SUBSTANCE);
                            $user->syncRoles($valsubRole);
                        } else if($members[$i]['role'] == 'examiner-administration') {
                            $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_ADMINISTRATION);
                            $user->syncRoles($valsubRole);
                        }
                    }
                }
            }

            return response()->json(['errors' => null, 'message' => 'success']);
        }

        if($request->member) {
            $members = $request->members;
            $deleted = $request->deleted;
            $receiver_chairman = [];
            $receiver_secretary = [];
            $receiver_member = [];
            $team_id = null;

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
                $user = User::where('email', $members[$i]['email'])->count();
                if($members[$i]['id']) {
                    if($members[$i]['role'] != $members[$i]['old_role']) {
                        if($user > 0) {
                            $user = User::where('email', $members[$i]['email'])->first();
                            if($members[$i]['role'] == 'Ketua') {
                                $receiver_chairman[] = $user;
                            } else if($members[$i]['role'] == 'Kepala Sekretariat') {
                                $receiver_secretary[] = $user;
                                $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SECRETARY);
                                $user->syncRoles($valsubRole);
                            } else if($members[$i]['role'] == 'Anggota') {
                                $receiver_member[] = $user;
                            }

                            // === HANDLE IF PREVIOUS POSITION IS SECRETARY === //
                            if($members[$i]['old_role'] == 'Kepala Sekretariat') {
                                $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SUBSTANCE);
                                $user->syncRoles($valsubRole);
                            }
                        }
                    }
                } else {
                    if($user > 0) {
                        $user = User::where('email', $members[$i]['email'])->first();

                        if($members[$i]['role'] == 'Kepala Sekretariat') {
                            $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SECRETARY);
                            $user->syncRoles($valsubRole);
                            
                            // ==== NOTIFICATION RECEIVER === //
                            $receiver_secretary[] = $user;

                        } else if($members[$i]['role'] == 'Ketua') {
                            $receiver_chairman[] = $user;
                        } else if($members[$i]['role'] == 'Anggota') {
                            $receiver_member[] = $user;
                        }
                    }
                }

                // === ASSIGN TEAM ID === //
                if($team_id == null) {
                    $team_id = $members[$i]['idTeam'];
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

            // === NOTIFICATIONS === //
            if($team_id != null) {
                $feasibility_test_team = FeasibilityTestTeam::findOrFail($team_id);
                if(count($receiver_chairman) > 0) {
                    Notification::send($receiver_chairman, new TUKAssigned($feasibility_test_team, 'Ketua'));
                }
                if(count($receiver_secretary) > 0) {
                    Notification::send($receiver_secretary, new TUKAssigned($feasibility_test_team, 'Kepala Sekretariat'));
                }
                if(count($receiver_member) > 0) {
                    Notification::send($receiver_secretary, new TUKAssigned($feasibility_test_team, 'Anggota'));
                }
            }

            return response()->json(['message' => 'success']);
        }
        
        $validator = $this->validateStore($request);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->messages()]);
        }

        $tuk = null;
        $team_number = null;

        if($request->type == 'create') {
            $tuk = new FeasibilityTestTeam();
            
            // === CHECK EXIST TUK TO ASSIGN TEAM NUMBER === //
            if($request->authority == 'Pusat') {
                $check_tuk = FeasibilityTestTeam::where('authority', 'Pusat')->count();
                if($check_tuk > 0) {
                    $check_tuk = FeasibilityTestTeam::where('authority', 'Pusat')->orderBy('id', 'desc')->first();
                    if($check_tuk->team_number == null) {
                        $team_number = 2;
                    } else {
                        $team_number = $check_tuk->team_number + 1;
                    }
                }
            } else if($request->authority == 'Provinsi') {
                $check_tuk = FeasibilityTestTeam::where([['authority', 'Provinsi'],['id_province_name', $request->id_province_name]])->count();
                if($check_tuk > 0) {
                    $check_tuk = FeasibilityTestTeam::where([['authority', 'Provinsi'],['id_province_name', $request->id_province_name]])->orderBy('id', 'desc')->first();
                    if($check_tuk->team_number == null) {
                        $team_number = 2;
                    } else {
                        $team_number = $check_tuk->team_number + 1;
                    }
                }
            } else if($request->authority == 'Kabupaten/Kota') {
                $check_tuk = FeasibilityTestTeam::where([['authority', 'Kabupaten/Kota'],['id_province_name', $request->id_province_name],['id_district_name', $request->id_district_name]])->count();
                if($check_tuk > 0) {
                    $check_tuk = FeasibilityTestTeam::where([['authority', 'Kabupaten/Kota'],['id_province_name', $request->id_province_name],['id_district_name', $request->id_district_name]])->orderBy('id', 'desc')->first();
                    if($check_tuk->team_number == null) {
                        $team_number = 2;
                    } else {
                        $team_number = $check_tuk->team_number + 1;
                    }
                }
            }

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

        if($request->type == 'create') {
            $tuk->team_number = $team_number;
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

    private function validateStore(Request $request)
    {
        $rules = [
            'authority' => 'required',
            'assignment_number' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'required',
        ];

        $messages = [
            'authority.required' => 'Kewenangan Wajib Dipilih',
            'assignment_number.required' => 'Nomor Penetapan Wajib Diisi',
            'email.required' => 'Email Wajib Diisi',
            'email.email' => 'Email Tidak Valid',
            'phone.required' => 'Nomor Kontak Wajib Diisi',
            'phone.numeric' => 'No Telepon Hanya Terdiri dari Angka',
            'address.required' => 'Alamat Wajib Dipilih',
        ];

        if($request->type == 'create') {
            $rules['assignment_file'] = 'required';
            $messages['assignment_file.required'] = 'File Wajib Diunggah';
        }

        if($request->authority) {
            if($request->authority == 'Provinsi' || $request->authority == 'Kabupaten/Kota') {
                $rules['id_province_name'] = 'required';
                $messages['id_province_name.required'] = 'Provinsi Wajib Dipilih';
            }
            if($request->authority == 'Kabupaten/Kota') {
                $rules['id_district_name'] = 'required';
                $messages['id_district_name.required'] = 'Kabupaten/Kota Wajib Dipilih';
            }
        }

        $data = $request->all();
        $validator = \Validator::make($data, $rules, $messages);

        return $validator;
    }
}
