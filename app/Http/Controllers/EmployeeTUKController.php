<?php

namespace App\Http\Controllers;

use App\Entity\FeasibilityTestTeam;
use App\Entity\FeasibilityTestTeamMember;
use App\Entity\LukMember;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use App\Notifications\ChangeUserEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class EmployeeTUKController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->type == 'edit') {
            $employee_tuk = LukMember::find($request->idEmployee);
            $id_team = null;

            if($employee_tuk->feasibilityTestTeamMember) {
                $id_team = $employee_tuk->feasibilityTestTeamMember->id_feasibility_test_team;
            }

            $employee_tuk->setAttribute('id_feasibility_test_team', $id_team);

            return $employee_tuk;
        }

        if($request->type == 'list') {
            $employees = LukMember::where(function($q) use($request) {
                $search = $request->search;
                if($search) {
                    $search = str_replace('tim', '', strtolower($search));
                    $search = str_replace('uji', '', $search);
                    $search = str_replace('kelayakan', '', $search);
                    $search = str_replace('provinsi', '', $search);
                    $search = trim($search);

                    $q->where(function($query) use($search) {
                        $query->whereRaw("LOWER(name) LIKE '%" . strtolower($search) . "%'");
                    })->orWhere(function($query) use($search) {
                        $query->whereRaw("LOWER(institution) LIKE '%" . strtolower($search) . "%'");
                    })->orWhere(function($query) use($search) {
                        $query->whereRaw("LOWER(position) LIKE '%" . strtolower($search) . "%'");
                    })->orWhere(function($query) use($search) {
                        $query->whereRaw("LOWER(nik) LIKE '%" . strtolower($search) . "%'");
                    })->orWhere(function($query) use($search) {
                        $query->whereHas('feasibilityTestTeamMember', function($que) use($search) {
                            $que->whereHas('feasibilityTestTeam', function($qu) use($search) {
                                if($search == 'pusat') {
                                    $qu->where('authority', 'Pusat');
                                } else {
                                    $qu->where(function($quer) use($search) {
                                        $quer->where('authority', 'Provinsi');
                                        $quer->whereHas('provinceAuthority', function($queryy) use($search) {
                                            $queryy->whereRaw("LOWER(name) LIKE '%" . strtolower($search) . "%'");
                                        });
                                    })->orWhere(function($quer) use($search) {
                                        $quer->where('authority', 'Kabupaten/Kota');
                                        $quer->whereHas('districtAuthority', function($queryy) use($search) {
                                            $queryy->whereRaw("LOWER(name) LIKE '%" . strtolower($search) . "%'");
                                        });
                                    });
                                }
                            });
                        });
                    });
                }
            })
            ->with(['province', 'district', 'feasibilityTestTeamMember.feasibilityTestTeam' => function($q) {
                $q->with('provinceAuthority');
                $q->with('districtAuthority');
            }])->orderBy('id', 'desc')->paginate($request->limit);
            return $employees;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = $request->type == 'create' ? $this->validateCreate($input) : $this->validateUpdate($input);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->messages()]);
        }
        
        DB::beginTransaction();
        $request_email = $request->email ? strtolower($request->email) : $request->email;

        $is_user_exist = User::where('email', $request_email)->count();
        if($is_user_exist == 0) {
            $valsubRole = Role::findByName(Acl::ROLE_EXAMINER_SUBSTANCE);
            $password = Str::random(8);
            $user = User::create([
                'name' => ucfirst($request->name),
                'email' => $request_email,
                'password' => Hash::make($password),
                'original_password' => $password
            ]);
            $user->syncRoles($valsubRole);
        }

        $employee_tuk = null;
        $email_changed_notif = null;
        $old_email = null;
        $password = Str::random(8);

        if($request->type == 'create') {
            $employee_tuk = new LukMember();
        } else {
            $employee_tuk = LukMember::findOrFail($request->idEmployee);

            // update user email
            if($request_email) {
                if($request_email != $employee_tuk->email) {
                    $found = User::where('email', $request_email)->first();
                    if($found) {
                        return response()->json(['errors' => 'Email yang anda masukkan sudah terpakai']);
                    } else {
                        if($employee_tuk->email) {
                            $employee_tuk_user = User::where('email', $employee_tuk->email)->first();
                            if($employee_tuk_user) {
                                $employee_tuk_user->name = $request->name;
                                $employee_tuk_user->email = $request_email;
                                $employee_tuk_user->password = Hash::make($password);
                                $employee_tuk_user->save();
                                $email_changed_notif = $employee_tuk_user;
                            } 
                        } 
                    }
                }
            }
        }

        $employee_tuk->status = $request->status;
        $employee_tuk->nik = $request->nik;
        $employee_tuk->nip = $request->nip;
        $employee_tuk->name = $request->name;
        $employee_tuk->institution = $request->institution;
        $employee_tuk->email = $request_email;
        $employee_tuk->position = $request->position;
        $employee_tuk->phone = $request->phone;
        $employee_tuk->sex = $request->sex;
        $employee_tuk->id_province = $request->id_province;
        $employee_tuk->id_district = $request->id_district;
        $employee_tuk->address = $request->address;

        if($request->hasFile('cv')) {
            $file = $request->file('cv');
            $name = 'cv/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);

            $employee_tuk->cv = $name;
        }

        $saved = $employee_tuk->save();

        if(!$saved) {
            DB::rollback();
        } else {
             // CHECK IF EMPLOYEE ALREADY ASSIGNED TO TUK
             if($request->id_feasibility_test_team) {
                 $check_tuk = FeasibilityTestTeamMember::where('id_luk_member', $employee_tuk->id)->first();
                 if($check_tuk) {
                     $old_id_team = $check_tuk->id_feasibility_test_team;
     
                     if($old_id_team != $request->id_feasibility_test_team) {
                         $check_tuk->id_feasibility_test_team = $request->id_feasibility_test_team;
                         $check_tuk->position = null;
                         $check_tuk->save();
                     }
                 } else {
                     $tuk_member = new FeasibilityTestTeamMember();
                     $tuk_member->id_feasibility_test_team = $request->id_feasibility_test_team;
                     $tuk_member->id_luk_member = $employee_tuk->id;
                     $tuk_member->save();
                 }
             }
             
            DB::commit();

            // send notification if existing user email changed
            if($email_changed_notif) {
                Notification::send([$email_changed_notif], new ChangeUserEmailNotification(null, null, null, $password));
                Notification::route('mail', $old_email)->notify(new ChangeUserEmailNotification($email_changed_notif->name, $email_changed_notif->email, $email_changed_notif->roles->first()->name));
            }
        }

        return response()->json(['error' => null, 'message' => 'success']);
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
        $member = LukMember::findOrFail($id);
        User::where('email', $member->email)->delete();
        LukMember::destroy($id);

        return response()->json(['message' => 'Data successfully deleted']);
    }

    private function validateCreate($data) {
        $validator = \Validator::make($data,[
            'status' => 'required',
            'nik' => 'required',
            'name' => 'required',
            'institution' => 'required',
            'email' => 'required|email|unique:luk_members,email',
            'position' => 'required',
            'phone' => 'required',
            'sex' => 'required',
            'id_province' => 'required',
            'id_district' => 'required',
            'address' => 'required',
            'cv' => 'max:1024'
        ],[
            'status.required' => 'Status Wajib Dipilih',
            'nik.required' => 'NIK Wajib Diisi',
            'name.required' => 'Nama Wajib Diisi',
            'institution.required' => 'Instansi Wajib Diisi',
            'email.required' => 'Email Wajib Diisi',
            'email.email' => 'Email Tidak Valid',
            'email.unique' => 'Email Sudah Terdaftar',
            'position.required' => 'Jabatan Wajib Diisi',
            'phone.required' => 'No. Telepon Wajib Diisi',
            'sex.required' => 'Jenis Kelamin Wajib Dipilih',
            'id_province.required' => 'Provinsi Wajib Dipilih',
            'id_district.required' => 'Kota/Kabupaten Wajib Dipilih',
            'address.required' => 'Alamat Wajib Diisi',
            'cv.max' => 'Ukuran file tidak boleh melebihi 1 MB'
        ]);

        return $validator;
    }

    private function validateUpdate($data) {
        $validator = \Validator::make($data,[
            'status' => 'required',
            'nik' => 'required',
            'name' => 'required',
            'institution' => 'required',
            'email' => 'required|email',
            'position' => 'required',
            'phone' => 'required',
            'sex' => 'required',
            'id_province' => 'required',
            'id_district' => 'required',
            'address' => 'required',
            'cv' => 'max:1024'
        ],[
            'status.required' => 'Status Wajib Dipilih',
            'nik.required' => 'NIK Wajib Diisi',
            'name.required' => 'Nama Wajib Diisi',
            'institution.required' => 'Instansi Wajib Diisi',
            'email.required' => 'Email Wajib Diisi',
            'email.email' => 'Email Tidak Valid',
            'email.unique' => 'Email Sudah Terdaftar',
            'position.required' => 'Jabatan Wajib Diisi',
            'phone.required' => 'No. Telepon Wajib Diisi',
            'sex.required' => 'Jenis Kelamin Wajib Dipilih',
            'id_province.required' => 'Provinsi Wajib Dipilih',
            'id_district.required' => 'Kota/Kabupaten Wajib Dipilih',
            'address.required' => 'Alamat Wajib Diisi',
            'cv.max' => 'Ukuran file tidak boleh melebihi 1 MB'
        ]);

        return $validator;
    }
}
