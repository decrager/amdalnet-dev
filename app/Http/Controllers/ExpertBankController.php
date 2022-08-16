<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBank;
use App\Http\Resources\ExpertBankResource;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use App\Notifications\ChangeUserEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ExpertBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ExpertBankResource::collection(
            ExpertBank::where(function ($query) use ($request) {
                if($request->search) {
                    $query->where(function($q) use($request) {
                        $q->whereRaw("LOWER(name) LIKE '%" . strtolower($request->search) . "%'");
                    })->orWhere(function($q) use($request) {
                        $q->whereRaw("LOWER(address) LIKE '%" . strtolower($request->search) . "%'");
                    })->orWhere(function($q) use($request) {
                        $q->whereRaw("LOWER(email) LIKE '%" . strtolower($request->search) . "%'");
                    })->orWhere(function($q) use($request) {
                        $q->whereRaw("LOWER(mobile_phone_no) LIKE '%" . strtolower($request->search) . "%'");
                    })->orWhere(function($q) use($request) {
                        $q->whereRaw("LOWER(expertise) LIKE '%" . strtolower($request->search) . "%'");
                    })->orWhere(function($q) use($request) {
                        $q->whereRaw("LOWER(institution) LIKE '%" . strtolower($request->search) . "%'");
                    })->orWhere(function($q) use($request) {
                        if(str_contains(strtolower($request->search), 'tidak aktif')) {
                            $q->whereIn('status', [null, false]);
                        } else if(str_contains(strtolower($request->search), 'aktif')) {
                            $q->where('status', true);
                        }
                    });
                }
            })->orderBy('id', 'DESC')->paginate($request->limit)
        );
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
                'name'              => 'required',
                'address'           => 'required',
                'email'             => 'required',
                'mobile_phone_no'   => 'required',
                'expertise'         => 'required',
                'institution'       => 'required',
                'status'            => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            DB::beginTransaction();

            //create file cv
            $fileCv = $request->file('cvFileUpload');
            $cvName = 'expert-bank/' . uniqid() . '.' . $fileCv->extension();
            $fileCv->storePubliclyAs('public', $cvName);

            //create file cv
            $fileIjasah = $request->file('ijasahFileUpload');
            $ijasahName = 'expert-bank/' . uniqid() . '.' . $fileIjasah->extension();
            $fileIjasah->storePubliclyAs('public', $ijasahName);

            //create file cv
            $fileCertLuk = $request->file('certLukFileUpload');
            $certLukName = 'expert-bank/' . uniqid() . '.' . $fileCertLuk->extension();
            $fileCertLuk->storePubliclyAs('public', $certLukName);

            //create file sertifikat
            $fileCertNonLuk = $request->file('certNonLukFileUpload');
            $fileCertNonLukName = 'expert-bank/' . uniqid() . '.' . $fileCertNonLuk->extension();
            $fileCertNonLuk->storePubliclyAs('public', $fileCertNonLukName);

            $email = $request->get('email');
            $found = User::where('email', $email)->first();
            if (!$found) {
                $expertRole = Role::findByName(Acl::ROLE_EXAMINER);
                $password = Str::random(8);
                $user = User::create([
                    'name' => ucfirst($params['name']),
                    'email' => $params['email'],
                    'password' => Hash::make($password),
                    'original_password' => $password
                ]);
                $user->syncRoles($expertRole);
            }


            //create Penyusun
            $expertBank = ExpertBank::create([
                'name'              => $params['name'],
                'address'           => $params['address'],
                'email'             => $params['email'],
                'mobile_phone_no'   => $params['mobile_phone_no'],
                'expertise'         => $params['expertise'],
                'institution'       => $params['institution'],
                'status'            => $params['status'],
                'cv_file'           => $cvName,
                'cert_luk_file'     => $certLukName,
                'cert_non_luk_file' => $fileCertNonLukName,
                'ijazah_file'       => $ijasahName,
            ]);


            if (!$expertBank) {
                DB::rollback();
            } else {
                DB::commit();
            }
            return new ExpertBankResource($expertBank);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\ExpertBank  $expertBank
     * @return \Illuminate\Http\Response
     */
    public function show(ExpertBank $expertBank)
    {
        //
    }

    public function showByEmail(Request $request)
    { 
        If($request->email){
            $expert = ExpertBank::where('email', $request->email)->first();
            
            if($expert) {
                return $expert;
            } else {
                return response()->json(null, 200);

            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\ExpertBank  $expertBank
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpertBank $expertBank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\ExpertBank  $expertBank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpertBank $expertBank)
    {
        if ($expertBank === null) {
            return response()->json(['error' => 'Bank Ahli Tidak Ditemukan'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name'              => 'required',
                'address'           => 'required',
                'email'             => 'required',
                'mobile_phone_no'   => 'required',
                'expertise'         => 'required',
                'institution'       => 'required',
                'status'            => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $email_changed_notif = null;
            $old_email = null;
            $password = Str::random(8);

            // update user email
            if($request->email) {
                if($request->email != $expertBank->email) {
                    $found = User::where('email', $request->email)->first();
                    if($found) {
                        return response()->json(['errors' => 'Email yang anda masukkan sudah terpakai']);
                    } else {
                        $create_user = 0;
                        if($expertBank->email) {
                            $expert_bank_user = User::where('email', $expertBank->email)->first();
                            if($expert_bank_user) {
                                $old_email = $expertBank->email;
                                $expert_bank_user->name = $request->name;
                                $expert_bank_user->email = $request->email;
                                $expert_bank_user->password = Hash::make($password);
                                $expert_bank_user->save();
                                $email_changed_notif = $expert_bank_user;
                            } else {
                                $create_user = 1;
                            }
                        } else {
                           $create_user = 1;
                        }

                        if($create_user == 1) {
                            $expertBankRole = Role::findByName(Acl::ROLE_EXAMINER);
                            $random_password = Str::random(8);
                            $user = User::create([
                                'name' => ucfirst($params['name']),
                                'email' => $params['email'],
                                'password' => isset($params['password']) ? Hash::make($params['password']) : Hash::make('amdalnet'),
                                'original_password' => isset($params['password']) ? $params['password'] : $random_password
                            ]);
                            $user->syncRoles($expertBankRole);
                        }
                    }
                }
            }

            $cvName = null;
            $ijasahName = null;
            $certLukName = null;
            $fileCertNonLukName = null;
            
            if($request->file('cvFileUpload') !== null){
                //create file cv
                $fileCv = $request->file('cvFileUpload');
                $cvName = 'expert-bank/' . uniqid() . '.' . $fileCv->extension();
                $fileCv->storePubliclyAs('public', $cvName);
                $expertBank->cv_file = $cvName;
            }

            if($request->file('ijasahFileUpload') !== null){
                //create file cv
                $fileIjasah = $request->file('ijasahFileUpload');
                $ijasahName = 'expert-bank/' . uniqid() . '.' . $fileIjasah->extension();
                $fileIjasah->storePubliclyAs('public', $ijasahName);
                $expertBank->ijazah_file = $ijasahName;
            }

            if($request->file('certLukFileUpload') !== null){
                //create file cv
                $fileCertLuk = $request->file('certLukFileUpload');
                $certLukName = 'expert-bank/' . uniqid() . '.' . $fileCertLuk->extension();
                $fileCertLuk->storePubliclyAs('public', $certLukName);
                $expertBank->cert_luk_file = $certLukName;
            }

            if($request->file('certNonLukFileUpload') !== null){
                //create file sertifikat
                $fileCertNonLuk = $request->file('certNonLukFileUpload');
                $fileCertNonLukName = 'expert-bank/' . uniqid() . '.' . $fileCertNonLuk->extension();
                $fileCertNonLuk->storePubliclyAs('public', $fileCertNonLukName);
                $expertBank->cert_non_luk_file = $fileCertNonLukName;
            }

            $expertBank->name = $params['name'];
            $expertBank->address = $params['address'];
            $expertBank->email = $params['email'];
            $expertBank->mobile_phone_no = $params['mobile_phone_no'];
            $expertBank->expertise = $params['expertise'];
            $expertBank->institution = $params['institution'];
            $expertBank->status = $params['status'];
            $expertBank->save();

            // send notification if existing user email changed
            if($email_changed_notif) {
                Notification::send([$email_changed_notif], new ChangeUserEmailNotification(null,null,null,$password));
                Notification::route('mail', $old_email)->notify(new ChangeUserEmailNotification($email_changed_notif->name, $email_changed_notif->email, $email_changed_notif->roles->first()->name));
            }
        }

        return new ExpertBankResource($expertBank);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\ExpertBank  $expertBank
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertBank $expertBank)
    {
        //
    }
}
