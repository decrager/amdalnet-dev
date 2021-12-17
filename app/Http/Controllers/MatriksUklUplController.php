<?php

namespace App\Http\Controllers;

use App\Entity\ImpactIdentification;
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
        // if ($type != 'ukl' && $type != 'upl'){
        //     return response()->json([
        //         'status' => 500,
        //         'code' => 500,
        //         'message' => 'Tipe matriks tidak valid.',
        //     ], 500);
        // }
        // $impacts = ImpactIdentification::from('impact_identifications AS ii')
        //     ->with(['envManagePlan', 'envMonitorPlan'])
        //     ->selectRaw('ii.id, ii.id_change_type, ct."name" as change_type_name,
        //         spc.id_project_stage,
        //         c.id_project_stage as id_project_stage_master,
        //         spc."name" as component_name,
        //         c."name" as component_name_master,
        //         spra."name" as rona_awal_name,
        //         ra."name" as rona_awal_name_master,
        //         ii."unit",
        //         u."name" as unit_master,
        //         ii.nominal')
        //     ->leftJoin('change_types AS ct', 'ii.id_change_type', '=', 'ct.id')
        //     ->leftJoin('sub_project_rona_awals AS spra', 'ii.id_sub_project_rona_awal', '=', 'spra.id')
        //     ->leftJoin('sub_project_components AS spc', 'ii.id_sub_project_component', '=', 'spc.id')
        //     ->leftJoin('components AS c', 'spc.id_component', '=', 'c.id')
        //     ->leftJoin('rona_awal AS ra', 'spra.id_rona_awal', '=', 'ra.id')
        //     ->leftJoin('units AS u', 'ii.id_unit', '=', 'u.id')
        //     ->where('ii.id_project', $id)
        //     ->orderBy('ii.id', 'asc')
        //     ->get();
        // $validated = [];
        // foreach ($impacts as $impact) {
        //     if (empty($impact->component_name) && empty($impact->component_name_master)) {
        //     } else {
        //         array_push($validated, $impact);
        //     }
        // }
        // return $validated;
    }
}
