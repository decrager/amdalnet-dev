<?php

namespace App\Http\Controllers;

use App\Entity\ImpactIdentification;
use App\Entity\ProjectStage;
use Illuminate\Http\Request;

class BesaranDampakController extends Controller
{
    public function getList(Request $request, $id)
    {
        $ids = [4,1,2,3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function($model) use($ids) {
            return array_search($model->getKey(),$ids);
        });
        $data = [];
        $impacts = $this->getImpactIdentifications($id);
        foreach ($stages as $stage) {
            $item = [];
            $item['is_stage'] = true;
            $item['project_stage_name'] = $stage->name;
            array_push($data, $item);
            foreach ($impacts as $impact) {
                if (empty($impact->id_project_stage)) {
                    $impact->id_project_stage = $impact->id_project_stage_master;
                }
                if (empty($impact->component_name)) {
                    $impact->component_name = $impact->component_name_master;
                }
                if (empty($impact->rona_awal_name)) {
                    $impact->rona_awal_name = $impact->rona_awal_name_master;
                }
                if (empty($impact->unit)) {
                    $impact->unit = $impact->nominal . ' ' . $impact->unit_master;
                }
                if ($impact->id_project_stage == $stage->id) {
                    $impact['is_stage'] = false;
                    array_push($data, $impact);
                }
            }
        }
        return $data;
    }

    private function getImpactIdentifications($id)
    {
        /*
        // commented by HH to comply with Pelingkupan ver 20220322
        $impacts = ImpactIdentification::from('impact_identifications AS ii')
            ->selectRaw('ii.id, ii.id_change_type,
                ii.change_type_name,
                ct."name" as change_type_name_master,
                spc.id_project_stage,
                c.id_project_stage as id_project_stage_master,
                spc."name" as component_name,
                c."name" as component_name_master,
                spra."name" as rona_awal_name,
                ra."name" as rona_awal_name_master,
                ii."unit",
                u."name" as unit_master,
                ii.nominal')
            ->leftJoin('change_types AS ct', 'ii.id_change_type', '=', 'ct.id')
            ->leftJoin('sub_project_rona_awals AS spra', 'ii.id_sub_project_rona_awal', '=', 'spra.id')
            ->leftJoin('sub_project_components AS spc', 'ii.id_sub_project_component', '=', 'spc.id')
            ->leftJoin('components AS c', 'spc.id_component', '=', 'c.id')
            ->leftJoin('rona_awal AS ra', 'spra.id_rona_awal', '=', 'ra.id')
            ->leftJoin('units AS u', 'ii.id_unit', '=', 'u.id')
            ->where('ii.id_project', $id)
            ->orderBy('ii.id', 'asc')
            ->get();*/
            $impacts = ImpactIdentification::from('impact_identifications AS ii')
            ->selectRaw('ii.id, ii.id_change_type,
                ii.change_type_name,
                ct."name" as change_type_name_master,
                c.id_project_stage,
                c.id_project_stage as id_project_stage_master,
                c."name" as component_name,
                c."name" as component_name_master,
                ra."name" as rona_awal_name,
                ra."name" as rona_awal_name_master,
                ii."unit",
                u."name" as unit_master,
                ii.nominal')
            ->leftJoin('change_types AS ct', 'ii.id_change_type', '=', 'ct.id')
            ->leftJoin('project_rona_awals AS spra', 'ii.id_project_rona_awal', '=', 'spra.id')
            ->leftJoin('project_components AS spc', 'ii.id_project_component', '=', 'spc.id')
            ->leftJoin('components AS c', 'spc.id_component', '=', 'c.id')
            ->leftJoin('rona_awal AS ra', 'spra.id_rona_awal', '=', 'ra.id')
            ->leftJoin('units AS u', 'ii.id_unit', '=', 'u.id')
            ->where('ii.id_project', $id)
            ->orderBy('ii.id', 'asc')
            ->get();
        $validated = [];
        foreach ($impacts as $impact) {
            if (empty($impact->component_name) && empty($impact->component_name_master)) {
            } else {
                array_push($validated, $impact);
            }
        }
        return $validated;
    }
}
