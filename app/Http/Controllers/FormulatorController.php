<?php

namespace App\Http\Controllers;

use App\Entity\Formulator;
use App\Http\Resources\FormulatorResource;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FormulatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return FormulatorResource::collection(
            Formulator::where(function ($query) use ($request) {
                if ($request->active) {
                    return $query->where([['date_start', '<=', date('Y-m-d H:i:s')], ['date_end', '>=', date('Y-m-d H:i:s')]])
                        ->orWhere([['date_start', null], ['date_end', '>=', date('Y-m-d H:i:s')]]);
                }
                return '';
            })->orderBy('id', 'DESC')->paginate($request->limit)->appends(['active' => $request->active])
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
                'expertise'         => 'required',
                // 'cert_no'           => 'required',
                // 'date_start'        => 'required',
                // 'date_end'          => 'required',
                // 'reg_no'            => 'required',
                // 'id_institution'    => 'required',
                // 'membership_status' => 'required',
                // 'id_lsp'            => 'required',
                'email'             => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            DB::beginTransaction();

            if ($request->file('cv_penyusun') !== null) {
                //create file cv
                $fileCv = $request->file('cv_penyusun');
                $cvName = '/penyusun/' . uniqid() . '.' . $fileCv->extension();
                $fileCv->storePubliclyAs('public', $cvName);
            }

            if ($request->file_sertifikat && ($request->file('file_sertifikat') !== null)) {
                //create file sertifikat
                $fileSertifikat = $request->file('file_sertifikat');
                $fileSertifikatName = '/penyusun/' . uniqid() . '.' . $fileSertifikat->extension();
                $fileCv->storePubliclyAs('public', $fileSertifikatName);
            }

            $email = $request->get('email');
            $found = User::where('email', $email)->first();
            if (!$found) {
                $formulatorRole = Role::findByName(Acl::ROLE_FORMULATOR);
                $user = User::create([
                    'name' => ucfirst($params['name']),
                    'email' => $params['email'],
                    'password' => isset($params['password']) ? Hash::make($params['password']) : Hash::make('amdalnet'),
                ]);
                $user->syncRoles($formulatorRole);
            }

            //create Penyusun
            $formulator = Formulator::create([
                'name'              => $params['name'],
                'expertise'         => $params['expertise'],
                'cert_no'           => isset($params['cert_no'])  ? $params['cert_no'] : null,
                'date_start'        => isset($params['date_start']) ? $params['date_start'] : null,
                'date_end'          => isset($params['date_end']) ? $params['date_end'] : null,
                'cert_file'         => isset($fileSertifikatName) ? Storage::url($fileSertifikatName) : null,
                'cv_file'           => isset($cvName) ? Storage::url($cvName) : null,
                'reg_no'            => isset($params['reg_no']) ? $params['reg_no'] : null,
                'id_institution'    => isset($params['id_institution']) ? $params['id_institution'] : null,
                'membership_status' => isset($params['membership_status']) ? $params['membership_status'] : null,
                'id_lsp'            => isset($params['id_lsp']) ? $params['id_lsp'] : null,
                'nik'               => isset($params['nik']) ? $params['nik'] : null,
                'district'          => isset($params['district']) ? $params['district'] : null,
                'province'          => isset($params['province']) ? $params['province'] : null,
                'phone'             => isset($params['phone']) ? $params['phone'] : null,
                'address'           => isset($params['address']) ? $params['address'] : null,
                'email'             => $params['email'],
            ]);

            if (!$formulator) {
                DB::rollback();
            } else {
                DB::commit();
            }

            return new FormulatorResource($formulator);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\Formulator  $formulator
     * @return \Illuminate\Http\Response
     */
    public function show(Formulator $formulator)
    {
        return $formulator;
    }

    public function showByEmail(Request $request)
    {
        if ($request->email) {
            $formulator = Formulator::where('email', $request->email)->first();

            if ($formulator) {
                return $formulator;
            } else {
                return response()->json(null, 200);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Formulator  $formulator
     * @return \Illuminate\Http\Response
     */
    public function edit(Formulator $formulator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Formulator  $formulator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Formulator $formulator)
    {
        if ($formulator === null) {
            return response()->json(['error' => 'formulator not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name'              => 'required',
                'expertise'         => 'required',
                'cert_no'           => 'required',
                'date_start'        => 'required',
                'date_end'          => 'required',
                'reg_no'            => 'required',
                'id_institution'    => 'required',
                'membership_status' => 'required',
                'id_lsp'            => 'required',
                'email'             => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            if ($request->file('cv_penyusun') !== null) {
                //create file cv
                $fileCv = $request->file('cv_penyusun');
                $cvName = '/penyusun/' . uniqid() . '.' . $fileCv->extension();
                $fileCv->storePubliclyAs('public', $cvName);
                $formulator->cv_file = Storage::url($cvName);
            }

            if ($request->file('file_sertifikat') !== null) {
                //create file sertifikat
                $fileSertifikat = $request->file('file_sertifikat');
                $fileSertifikatName = '/penyusun/' . uniqid() . '.' . $fileSertifikat->extension();
                $fileCv->storePubliclyAs('public', $fileSertifikatName);
                $formulator->cv_file = Storage::url($fileSertifikatName);
            }

            $formulator->name = $params['name'];
            $formulator->expertise = $params['expertise'];
            $formulator->cert_no = $params['cert_no'];
            $formulator->date_start = $params['date_start'];
            $formulator->date_end = $params['date_end'];
            $formulator->reg_no = $params['reg_no'];
            $formulator->id_institution = $params['id_institution'];
            $formulator->membership_status = $params['membership_status'];
            $formulator->id_lsp = $params['id_lsp'];
            $formulator->email = $params['email'];
            $formulator->nip = $params['nip'] ? $params['nip'] : null;
            $formulator->save();
        }

        return new FormulatorResource($formulator);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Formulator  $formulator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Formulator $formulator)
    {
        //
    }

    public function getFormulatorName()
    {
        $getData = DB::table('formulators')
            ->select('formulators.id', 'formulators.name')
            ->get();

        return response()->json($getData);
    }
}
