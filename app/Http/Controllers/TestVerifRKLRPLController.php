<?php

namespace App\Http\Controllers;

use App\Entity\KaForm;
use App\Entity\Project;
use App\Entity\ProjectKaForm;
use App\Entity\TestingVerification;
use Illuminate\Http\Request;

class TestVerifRKLRPLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->project) {
            return Project::whereHas('impactIdentifications')->get();
        }

        if($request->idProject) {
            // Check if verification exist
            $verifications = TestingVerification ::where([['id_project', $request->idProject], ['document_type', 'rkl-rpl']])->first();
            if($verifications) {
                return $this->getExistVerification($request->idProject);
            } else {
                return $this->getFreshVerification($request->idProject);
            }
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
        $data = $request->verifications;

        $verification = null;

        // Save verifications
        if($data['type'] == 'new') {
            $verification = new TestingVerification();
            $verification->id_project = $request->idProject;
            $verification->document_type = 'rkl-rpl';
        } else {
            $verification = TestingVerification::where([['id_project', $request->idProject],['document_type', 'rkl-rpl']])->first();
        }
        
        $verification->approved_by_position = $data['approved_by_position'];
        $verification->approved_in = $data['approved_in'];
        $verification->approved_date = date('Y-m-d');
        $verification->approved_by_name = $data['approved_by_name'];
        $verification->checked_in = $data['checked_in'];
        $verification->checked_date = date('Y-m-d');
        $verification->checked_by = $data['checked_by'];
        $verification->save();

        // Save Verifications form
        for($i = 0; $i < count($data['ka_forms']); $i++) {
            $form = null;

            if($data['type'] == 'new') {
                $form = new ProjectKaForm();
                $form->id_testing_verification = $verification->id;
                $form->id_ka_form = $data['ka_forms'][$i]['id_ka_form'];
            } else {
                $form = ProjectKaForm::where([['id_testing_verification', $verification->id], ['id_ka_form', $data['ka_forms'][$i]['id_ka_form']]])->first();
            }

            $form->completeness = $data['ka_forms'][$i]['completeness'];
            $form->suitability = $data['ka_forms'][$i]['suitability'];
            $form->description = $data['ka_forms'][$i]['description'];
            $form->save();
        }

        return response()->json(['message' => 'success']);
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
        //
    }

    private function getFreshVerification($id_project) {
        $ka_form = KaForm::where('document_type', 'rkl rpl')->get();
        
        $form = [];

        foreach($ka_form as $k) {
            $form[] = [
                'id_ka_form' => $k->id,
                'name' => $k->name,
                'completeness' => null,
                'suitability' => null,
                'description' => null
            ];
        }

        $data = [
            'type' => 'new',
            'id_project' => $id_project,
            'approved_by_position' => null,
            'approved_in' => null,
            'approved_date' => date('d F Y'),
            'approved_by_name' => null,
            'checked_in' => null,
            'checked_date' => date('d F Y'),
            'checked_by' => null,
            'ka_forms' => $form
        ];

        return $data;
    }

    private function getExistVerification($id_project) {

        $verification = TestingVerification::where([['id_project', $id_project],['document_type', 'rkl-rpl']])->first();
        
        $form = [];

        foreach($verification->forms as $f) {
            $form[] = [
                'id_ka_form' => $f->id,
                'name' => $f->kaForm->name,
                'completeness' => $f->completeness,
                'suitability' => $f->suitability,
                'description' => $f->description
            ];
        }

        $data = [
            'type' => 'update',
            'id_project' => $id_project,
            'approved_by_position' => $verification->approved_by_position,
            'approved_in' => $verification->approved_in,
            'approved_date' => date('d F Y', strtotime($verification->approved_date)),
            'approved_by_name' => $verification->approved_by_name,
            'checked_in' => $verification->checked_in,
            'checked_date' => date('d F Y', strtotime($verification->checked_date)),
            'checked_by' => $verification->checked_by,
            'ka_forms' => $form
        ];

        return $data;
    }
}
