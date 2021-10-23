<?php

namespace App\Http\Controllers;

use App\Entity\Formulator;
use App\Http\Resources\FormulatorResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
                'expertise'         => 'required',
                'cert_no'           => 'required',
                'date_start'        => 'required',
                'date_end'          => 'required',
                'reg_no'            => 'required',
                'id_institution'    => 'required',
                'membership_status' => 'required',
                'id_lsp'            => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            //create file cv
            $fileCv = $request->file('cv_penyusun');
            $cvName = '/penyusun/' . uniqid() . '.' . $fileCv->extension();
            $fileCv->storePubliclyAs('public', $cvName);

            //create file sertifikat
            $fileSertifikat = $request->file('file_sertifikat');
            $fileSertifikatName = '/penyusun/' . uniqid() . '.' . $fileSertifikat->extension();
            $fileCv->storePubliclyAs('public', $fileSertifikatName);

            //create Penyusun
            $formulator = Formulator::create([
                'name'              => $params['name'],
                'expertise'         => $params['expertise'],
                'cert_no'           => $params['cert_no'],
                'date_start'        => $params['date_start'],
                'date_end'          => $params['date_end'],
                'cert_file'         => Storage::url($fileSertifikatName),
                'cv_file'           => Storage::url($cvName),
                'reg_no'            => $params['reg_no'],
                'id_institution'    => $params['id_institution'],
                'membership_status' => $params['membership_status'],
                'id_lsp'            => $params['id_lsp'],
            ]);

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
        //
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
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            if($request->file('cv_penyusun') !== null){
                //create file cv
                $fileCv = $request->file('cv_penyusun');
                $cvName = '/penyusun/' . uniqid() . '.' . $fileCv->extension();
                $fileCv->storePubliclyAs('public', $cvName);
                $formulator->cv_file = Storage::url($cvName);
            }

            if($request->file('file_sertifikat') !== null){
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
}
