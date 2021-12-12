<?php

namespace App\Http\Controllers;

use App\Entity\EligibilityCriteria;
use App\Entity\FeasibilityTest;
use App\Entity\FeasibilityTestDetail;
use Illuminate\Http\Request;

class FeasibilityTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->idProject) {
            $feasibility = FeasibilityTest::where('id_project', $request->idProject)->first();
            if($feasibility) {
                return $this->getExistData($request->idProject);
            } else {
                return $this->getFreshData($request->idProject);
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
        $data = $request->feasibility;

        $feasibility = null;
        
        if($data['type'] == 'new') {
            $feasibility = new FeasibilityTest();
            $feasibility->id_project = $data['idProject'];
        } else {
            $feasibility = FeasibilityTest::where('id_project', $data['idProject'])->first();
        }

        $feasibility->conclusion = $data['conclusion'];
        $feasibility->save();

        for($i = 0; $i < count($data['detail']); $i++) {

            $feasibility_detail = null;

            if($data['detail'][$i]['type'] == 'new') {
                $feasibility_detail = new FeasibilityTestDetail();
                $feasibility_detail->id_feasibility_test = $feasibility->id;
                $feasibility_detail->id_eligibility_criteria = $data['detail'][$i]['id'];
            } else {
                $feasibility_detail = FeasibilityTestDetail::where([['id_feasibility_test', $feasibility->id],['id_eligibility_criteria',$data['detail'][$i]['id']]])->first();
            }

            $feasibility_detail->appropriateness = $data['detail'][$i]['appropriateness'];
            $feasibility_detail->notes = $data['detail'][$i]['notes'];
            $feasibility_detail->save();
        }

        return response()->json(['messsage' => 'success']);
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

    private function getExistData($idProject) {
        $feasibility = FeasibilityTest::where('id_project', $idProject)->first();

        $data = [];
        
        if($feasibility->detail->first()) {
            foreach($feasibility->detail as $d) {
                $data[] = [
                    'id' => $d->id_eligibility_criteria,
                    'description' => $d->eligibility->description,
                    'appropriateness' => $d->appropriateness,
                    'notes' => $d->notes,
                    'type' => 'update'
                ];
            }

            usort($data, function($a, $b) {
                return $a['id'] <=> $b['id'];
            });
        }

        $eligibility = EligibilityCriteria::whereDoesntHave('feasibility', function($q) use($feasibility) {
            $q->where('id_feasibility_test', $feasibility->id);
        });

        foreach($eligibility as $e) {
            $data[] = [
                'id' => $e->id,
                'description' => $e->description,
                'appropriateness' => null,
                'notes' => null,
                'type' => 'new'
            ]; 
        }

        return [
            'type' => 'update',
            'id' => $feasibility->id,
            'idProject' => $idProject,
            'conclusion' => $feasibility->conclusion,
            'detail' => $data
        ];
    }

    private function getFreshData($idProject) {
        $data = [];
        $eligibility = EligibilityCriteria::all();

        foreach($eligibility as $e) {
            $data[] = [
                'id' => $e->id,
                'description' => $e->description,
                'appropriateness' => null,
                'notes' => null,
                'type' => 'new'
            ]; 
        }

        return [
            'type' => 'new',
            'id' => null,
            'idProject' => $idProject,
            'conclusion' => null,
            'detail' => $data
        ];
    }
}
