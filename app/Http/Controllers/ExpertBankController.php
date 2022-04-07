<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBank;
use App\Http\Resources\ExpertBankResource;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use DB;

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
            $cvName = '/expert-bank/' . uniqid() . '.' . $fileCv->extension();
            $fileCv->storePubliclyAs('public', $cvName);

            //create file cv
            $fileIjasah = $request->file('ijasahFileUpload');
            $ijasahName = '/expert-bank/' . uniqid() . '.' . $fileIjasah->extension();
            $fileIjasah->storePubliclyAs('public', $ijasahName);

            //create file cv
            $fileCertLuk = $request->file('certLukFileUpload');
            $certLukName = '/expert-bank/' . uniqid() . '.' . $fileCertLuk->extension();
            $fileCertLuk->storePubliclyAs('public', $certLukName);

            //create file sertifikat
            $fileCertNonLuk = $request->file('certNonLukFileUpload');
            $fileCertNonLukName = '/expert-bank/' . uniqid() . '.' . $fileCertNonLuk->extension();
            $fileCertNonLuk->storePubliclyAs('public', $fileCertNonLukName);

            $email = $request->get('email');
            $found = User::where('email', $email)->first();
            if (!$found) {
                $expertRole = Role::findByName(Acl::ROLE_EXAMINER);
                $user = User::create([
                    'name' => ucfirst($params['name']),
                    'email' => $params['email'],
                    'password' => Hash::make('amdalnet')
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
                'cv_file'           => Storage::url($cvName),
                'cert_luk_file'     => Storage::url($certLukName),
                'cert_non_luk_file' => Storage::url($fileCertNonLukName),
                'ijazah_file'       => Storage::url($ijasahName),
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

            $cvName = null;
            $ijasahName = null;
            $certLukName = null;
            $fileCertNonLukName = null;
            
            if($request->file('cvFileUpload') !== null){
                //create file cv
                $fileCv = $request->file('cvFileUpload');
                $cvName = '/expert-bank/' . uniqid() . '.' . $fileCv->extension();
                $fileCv->storePubliclyAs('public', $cvName);
            }

            if($request->file('ijasahFileUpload') !== null){
                //create file cv
                $fileIjasah = $request->file('ijasahFileUpload');
                $ijasahName = '/expert-bank/' . uniqid() . '.' . $fileIjasah->extension();
                $fileIjasah->storePubliclyAs('public', $ijasahName);
            }

            if($request->file('certLukFileUpload') !== null){
                //create file cv
                $fileCertLuk = $request->file('certLukFileUpload');
                $certLukName = '/expert-bank/' . uniqid() . '.' . $fileCertLuk->extension();
                $fileCertLuk->storePubliclyAs('public', $certLukName);
            }

            if($request->file('certNonLukFileUpload') !== null){
                //create file sertifikat
                $fileCertNonLuk = $request->file('certNonLukFileUpload');
                $fileCertNonLukName = '/expert-bank/' . uniqid() . '.' . $fileCertNonLuk->extension();
                $fileCertNonLuk->storePubliclyAs('public', $fileCertNonLukName);
            }

            $expertBank->name = $params['name'];
            $expertBank->address = $params['address'];
            $expertBank->email = $params['email'];
            $expertBank->mobile_phone_no = $params['mobile_phone_no'];
            $expertBank->expertise = $params['expertise'];
            $expertBank->institution = $params['institution'];
            $expertBank->status = $params['status'];
            $expertBank->cv_file = $cvName ? Storage::url($cvName) : $expertBank->cv_file;
            $expertBank->cert_luk_file = $certLukName ? Storage::url($certLukName) : $expertBank->cert_luk_file;
            $expertBank->cert_non_luk_file = $fileCertNonLukName ? Storage::url($fileCertNonLukName) : $expertBank->cert_non_luk_file;
            $expertBank->ijazah_file = $ijasahName ? Storage::url($ijasahName) :$expertBank->ijazah_file;
            $expertBank->save();
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
