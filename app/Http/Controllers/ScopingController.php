<?php

namespace App\Http\Controllers;

use App\Entity\ComponentType;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactStudy;
use App\Entity\SubProject;
use App\Entity\SubProjectComponent;
use App\Entity\SubProjectRonaAwal;
use App\Http\Resources\SubProjectComponentResource;
use App\Http\Resources\SubProjectResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScopingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->check_formulir_ka && $request->id_project) {
            return $this->checkFormulirKA($request);
        }
        return $this->getScopingData($request);        
    }

    private function checkFormulirKA(Request $request) {    
        // check if formulir KA is complete
        try {
            $impacts = ImpactIdentification::select('id')
                ->where('id_project', $request->id_project)
                ->orderBy('id', 'asc')
                ->get();
            $impactStudies = ImpactStudy::from('impact_studies AS is2')
                ->selectRaw('is2.id_impact_identification AS id')
                ->leftJoin('impact_identifications AS ii', 'is2.id_impact_identification', '=', 'ii.id')
                ->where('ii.id_project', $request->id_project)
                ->orderBy('id', 'asc')
                ->get();
            $id = [];
            $id2 = [];
            foreach ($impacts as $impact) {
                array_push($id, $impact->id);
            }
            foreach ($impactStudies as $impactStudy) {
                array_push($id2, $impactStudy->id);
            }
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => empty($id) && empty($id2) ? false : $id == $id2,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function getSubProjects(Request $request) {
        $subprojects = SubProject::with('project')
            ->where('id_project', $request->id_project)
            ->where('type', $request->sub_project_type) // 'utama'/'pendukung'
            ->get();
        return SubProjectResource::collection($subprojects);
    }

    private function getComponents(Request $request) {
        // return sub_project_components filter by id_sub_project & id_project_stage
        $id_project_stage = $request->id_project_stage;
        $id_sub_project = $request->id_sub_project;
        $level1 = SubProjectComponent::with('component')
            ->where('id_sub_project', $id_sub_project)
            ->where('id_project_stage', $id_project_stage)
            ->get();
        $nested = SubProjectComponent::with('component')->whereHas('component', function($q) use ($id_project_stage) {
                $q->where('id_project_stage', $id_project_stage);
            })->where('id_sub_project', $id_sub_project)->get();
        return $level1->merge($nested);
    }

    private function getRonaAwals(Request $request) {
        $id_sub_project_component = $request->id_sub_project_component;
        $rona_awals = [];
        $component_types = ComponentType::select('*')->orderBy('id', 'asc')->get();
        foreach ($component_types as $ctype) {
            $sp_rona_awals = SubProjectRonaAwal::with(['ronaAwal'])
                ->where('id_sub_project_component', $id_sub_project_component)->get();                            
            $rawals = [];
            foreach ($sp_rona_awals as $rona_awal) {
                if ($ctype->id == $rona_awal->id_component_type) {
                    array_push($rawals,  $rona_awal);
                }
                if ($rona_awal->ronaAwal != null) {
                    if ($ctype->id == $rona_awal->ronaAwal->id_component_type) {
                        array_push($rawals,  $rona_awal);
                    }
                }
            }
            array_push($rona_awals, [
                'component_type' => $ctype,
                'rona_awals' => $rawals,
            ]);
        }
        return [
            'data' => $rona_awals,
        ];
    }

    private function getScopingData(Request $request) {
        if ($request->id_project && $request->sub_project_type) {
            return $this->getSubProjects($request);
        }
        if ($request->id_sub_project && $request->id_project_stage) {
            $components = $this->getComponents($request);
            if ($request->sub_project_components) {
                return SubProjectComponentResource::collection($components);
            }
        }
        if ($request->sub_project_rona_awals && $request->id_sub_project_component) {
            return $this->getRonaAwals($request);
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
        if ($request->component) {
            $params = $request->all();
            $component = $params['component'];
            $invalid_component = (!isset($component['id_component']) || $component['id_component'] == null)
                && (!isset($component['name']) || $component['name'] == null);
            $invalid_description = (!isset($component['description_specific']) || $component['description_specific'] == null);
            $invalid_unit = (!isset($component['unit']) || $component['unit'] == null);
            $errors = [];
            if ($invalid_component){
                array_push($errors, 'Komponen kegiatan wajib diisi');
            }
            if ($invalid_description){
                array_push($errors, 'Deskripsi wajib diisi');
            }
            if ($invalid_unit){
                array_push($errors, 'Besaran wajib diisi');
            }
            if (count($errors) > 0) {
                return response()->json(['errors' => join(', ', $errors)], 403);
            }            
            if (isset($component['id_component']) && $component['id_component'] != null) {
                $component['name'] = null;
                $component['id_project_stage'] = null;
            }
            DB::beginTransaction();            
            if (isset($component['id']) && $component['id'] != null) {                
                // edit
                $edit = SubProjectComponent::find($component['id']);
                $edit->name = $component['name'];
                $edit->description_specific = $component['description_specific'];
                $edit->unit = $component['unit'];
                $saved = $edit->save();
                if ($saved) {
                    DB::commit();
                    return [
                        'data' => $saved,
                    ];
                } else {
                    DB::rollBack();
                }
            } else {
                // create new
                $createdComp = SubProjectComponent::create($component);
                if ($createdComp) {
                    DB::commit();
                    return [
                        'data' => $createdComp,
                    ];
                } else {
                    DB::rollBack();
                }
            }
        } else if ($request->rona_awal) {
            $params = $request->all();
            $rona_awal = $params['rona_awal'];
            $invalid_rona = (!isset($rona_awal['id_rona_awal']) || $rona_awal['id_rona_awal'] == null)
                && (!isset($rona_awal['name']) || $rona_awal['name'] == null);
            $invalid_description = (!isset($rona_awal['description_specific']) || $rona_awal['description_specific'] == null);
            $invalid_unit = (!isset($rona_awal['unit']) || $rona_awal['unit'] == null);
            $errors = [];
            if ($invalid_rona){
                array_push($errors, 'Komponen lingkungan wajib diisi');
            }
            if ($invalid_description){
                array_push($errors, 'Deskripsi wajib diisi');
            }
            if ($invalid_unit){
                array_push($errors, 'Besaran wajib diisi');
            }
            if (count($errors) > 0) {
                return response()->json(['errors' => join(', ', $errors)], 403);
            }
            if (isset($rona_awal['id_rona_awal']) && $rona_awal['id_rona_awal'] != null) {
                $rona_awal['name'] = null;
                $rona_awal['id_component_type'] = null;
            }
            DB::beginTransaction();
            $createdRa = SubProjectRonaAwal::create($rona_awal);
            if ($createdRa) {
                DB::commit();
                return [
                    'data' => $createdRa,
                ];
            } else {
                DB::rollBack();
            }
        }
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
}
