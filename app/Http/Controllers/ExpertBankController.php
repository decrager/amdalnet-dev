<?php

namespace App\Http\Controllers;

use App\Entity\ExpertBank;
use App\Http\Resources\ExpertBankResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
                return '';
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

            //create Penyusun
            $expertBank = ExpertBank::create([
                'name'              => $params['name'],
                'address'           => $params['address'],
                'email'             => $params['email'],
                'mobile_phone_no'   => $params['mobile_phone_no'],
                'expertise'         => $params['expertise'],
                'institution'       => $params['expertise'],
                'status'            => $params['status'],
                'cv_file'           => Storage::url($cvName),
                'cert_luk_file'     => Storage::url($certLukName),
                'cert_non_luk_file' => Storage::url($fileCertNonLukName),
                'ijazah_file'       => Storage::url($ijasahName),
            ]);

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
        //
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
