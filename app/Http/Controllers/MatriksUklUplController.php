<?php

namespace App\Http\Controllers;

use App\Entity\ImpactIdentification;
use App\Entity\ProjectStage;
use Illuminate\Http\Request;

class MatriksUklUplController extends Controller
{
    public function getTableUkl(Request $request, $id)
    {
        return $this->getTableData($request, $id, 'ukl');
    }

    public function getTableUpl(Request $request, $id)
    {
        return $this->getTableData($request, $id, 'upl');
    }

    private function getTableData(Request $request, $id, $type)
    {
        if ($type != 'ukl' && $type != 'upl'){
            return response()->json([
                'status' => 500,
                'code' => 500,
                'message' => 'Tipe matriks tidak valid.',
            ], 500);
        }
        $with = '';
        if ($type == 'ukl'){
            $with = 'envManagePlan';
        } else if ($type == 'upl'){
            $with = 'envMonitorPlan';
        }
        $impacts = ImpactIdentification::from('impact_identifications AS ii')
            ->with($with)
            ->selectRaw('ii.id, ii.id_change_type, 
                ct."name" as change_type_name,
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
            ->get();
        $ids = [4,1,2,3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function($model) use($ids) {
            return array_search($model->getKey(),$ids);
        });
        $data = [];
        $index = 1;
        foreach ($stages as $stage) {
            $index = 1;
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
                    $impact['index'] = $index;
                    if ($type == 'ukl'){
                        if ($impact->envManagePlan != null) {
                            foreach ($impact->envManagePlan as $idx=>$plan) {
                                if ($idx == 0) {
                                    $plan['is_selected'] = true;
                                } else {
                                    $plan['is_selected'] = false;
                                }
                            }
                        }
                    } else if ($type == 'upl'){
                        if ($impact->envMonitorPlan != null) {
                            foreach ($impact->envMonitorPlan as $idx=>$plan) {
                                if ($idx == 0) {
                                    $plan['is_selected'] = true;
                                } else {
                                    $plan['is_selected'] = false;
                                }
                            }
                        }
                    }
                    array_push($data, $impact);
                    $index++;
                }
            }
        }
        return $data;
    }
}
