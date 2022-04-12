<?php

namespace App\Http\Controllers;

use App\Entity\ComponentType;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactIdentificationClone;
use App\Entity\ProjectStage;
use App\Entity\SubProjectComponent;
use App\Entity\SubProjectRonaAwal;
use App\Entity\ProjectRonaAwal;
use App\Entity\ProjectComponent;
use Illuminate\Http\Request;
use Exception;
use App\Entity\SubProject;

class MatriksDampakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTable(Request $request, $id)
    {
        return $this->getTableData($request, $id);
    }

    public function getTableDph(Request $request, $id)
    {
        return $this->getTableData($request, $id, true);
    }

    public function getRonaMapping($id)
    {
        try {
            $all_component_types = ComponentType::select('*')->orderBy('id', 'asc')->get();
            $rona_awals = $this->getRonaAwals($id);
            $data = [];
            foreach ($all_component_types as $ctype){
                $key = $ctype->name;
                $ra_list = [];
                $ra_names = [];
                foreach ($rona_awals as $ra) {
                    if ($ra->id_component_type == $ctype->id
                        || $ra->id_component_type_master == $ctype->id) {
                        if (empty($ra->name)) {
                            $ra->name = $ra->name_master;
                        }
                        $ra_key = preg_replace('/[^a-zA-Z0-9_.]/', '_', strtolower($ra->name));
                        if (!in_array(strtolower($ra->name), $ra_names)) {
                            array_push($ra_list, [
                                'key' => $ra_key,
                                'name' => ucfirst($ra->name),
                                'id' => $ra->id,
                            ]);
                        }
                        array_push($ra_names, $ra->name);
                    }
                }
                if (count($ra_list) > 0){
                    $data[$key] = $ra_list;
                }
            }
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function getComponentRonas(Request $request, $id)
    {
        $impact_table = ($request->isAndal === true) ? 'impact_identification_clones' : 'impact_identifications';
        return ProjectRonaAwal::from('project_rona_awals as pra')
            ->select('pra.id', 'c.name as component_name', 'ra.name as rona_awal_name',
            'c.name AS component_name_master', 'ra.name AS rona_awal_name_master',
            'c.id_project_stage', 'c.id_project_stage AS id_project_stage_master'

            )
            ->join( $impact_table.' as ii', 'ii.id_project_rona_awal', '=', 'pra.id')
            ->join('project_components as pc', 'pc.id', '=', 'ii.id_project_component')
            ->leftJoin('components as c', 'c.id', '=', 'pc.id_component')
            ->leftJoin('rona_awal as ra', 'ra.id', '=', 'pra.id_rona_awal')
            ->where('ii.id_project', $id)->get();

            /*
        // commented by HH, 20220329
        return SubProjectRonaAwal::from('sub_project_rona_awals AS spra')
            ->select('spra.id', 'c.name AS component_name', 'ra.name AS rona_awal_name',
                'c.name AS component_name_master', 'ra.name AS rona_awal_name_master',
                'spc.id_project_stage',
                'c.id_project_stage AS id_project_stage_master')
            ->leftJoin('sub_project_components AS spc', 'spra.id_sub_project_component', '=', 'spc.id')
            ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
            ->leftJoin('components AS c', 'spc.id_component', '=', 'c.id')
            ->leftJoin('rona_awal AS ra', 'spra.id_rona_awal', '=', 'ra.id')
            ->where('sp.id_project', $id)
            ->whereNotNull('spc.id')
            ->whereNotNull('spra.id')
            ->get();
        */
    }

    private function getTableData(Request $request, $id, $is_dph = false)
    {
        $data = [];
        $rona_mapping = $this->getRonaMapping($id);

        $components_by_stage = $this->getComponentsGroupByStage($id);
        $component_ronas = $this->getComponentRonas($request, $id);

        // return response($components_by_stage);

        foreach ($components_by_stage as $cstage) {
            $index = 1;
            $item = [];
            $item['type'] = 'stage';
            $item['component_name'] = $cstage['project_stage_name'];
            $item['component_types'] = [];
            array_push($data, $item);
            foreach ($cstage['components'] as $component) {
                $item['type'] = 'component';
                $item['component_name'] = $index . '. ' . ucfirst($component->name);
                $ctypes = [];
                foreach ($rona_mapping as $key => $rm) {
                    $ctype_master = ComponentType::where('name', $key)->first();
                    $ctype = [
                        'id' => $ctype_master->id, // find ctype id
                        'name' => $key,
                    ];
                    foreach ($rona_mapping[$key] as $ra) {
                        $k = $ra['key'];
                        $ctype[$k] = ' ';
                        foreach ($component_ronas as $cr) {
                            if (empty($cr->component_name)) {
                                $cr->component_name = $cr->component_name_master;
                            }
                            if (empty($cr->rona_awal_name)) {
                                $cr->rona_awal_name = $cr->rona_awal_name_master;
                            }
                            if (empty($cr->id_project_stage)) {
                                $cr->id_project_stage = $cr->id_project_stage_master;
                            }
                            if ($cr->id_project_stage == $cstage['id_project_stage']
                                && strtolower($cr->component_name) == strtolower($component->name)
                                && strtolower($cr->rona_awal_name) == strtolower($ra['name'])) {
                                if ($is_dph) {
                                    // check if DPH
                                    $dph = null;
                                    $impClass = ($request->isAndal === 'true') ? 'App\Entity\ImpactIdentificationClone' : 'App\Entity\ImpactIdentification';
                                    $impTable = ($request->isAndal === 'true') ? 'impact_identification_clones' : 'impact_identifications';

                                    $dph = $impClass::from($impTable)->select($impTable.'.is_hypothetical_significant')
                                        ->join('project_components', 'project_components.id', '=', $impTable.'.id_project_component')
                                        ->where('project_components.id_component', $component->id_component)
                                        ->where($impTable.'.id_project_rona_awal', $cr->id)->first();

                                    /*if($request->isAndal === 'true') {
                                        $dph = ImpactIdentificationClone::select('is_hypothetical_significant')
                                            ->where('id_project_rona_awal', $cr->id)
                                            ->first();
                                    } else {
                                        $dph = ImpactIdentification::select('is_hypothetical_significant')
                                            ->where('id_project_rona_awal', $cr->id)
                                            ->first();
                                    }*/
                                    if($dph !== null){
                                        if ($dph->is_hypothetical_significant === true) {
                                            $ctype[$k] = 'DPH';
                                        } else if ($dph->is_hypothetical_significant === false){
                                            $ctype[$k] = 'DTPH';
                                        }
                                    }
                                } else {
                                    $ctype[$k] = 'v';
                                }
                                break;
                            }
                        }
                    }
                    array_push($ctypes, $ctype);
                }
                $item['component_types'] = $ctypes;
                array_push($data, $item);
                $index++;
            }
        }
        return $data;
    }

    private function getRonaAwals($id) {
      /*  return ProjectRonaAwal::from('project_rona_awals AS spra')
            ->selectRaw('spra.id, lower(ra.name) as name,spra.id_component_type,
                lower(ra.name) AS name_master, ra.id_component_type AS id_component_type_master')
            ->leftJoin('project_components AS spc', 'spra.id_project_component', '=', 'spc.id')
            ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
            ->leftJoin('rona_awal AS ra', 'spra.id_rona_awal', '=', 'ra.id')
            ->where('sp.id_project', $id)
            // ->whereNotNull('spc.id')
            // ->whereNotNull('spra.id')
            ->orderBy('spra.id', 'asc')
            ->get();
*/
        // commented by HH, 20220328

        return SubProjectRonaAwal::from('sub_project_rona_awals AS spra')
            ->selectRaw('spra.id, lower(spra.name) as name,spra.id_component_type,
                lower(ra.name) AS name_master, ra.id_component_type AS id_component_type_master')
            ->leftJoin('sub_project_components AS spc', 'spra.id_sub_project_component', '=', 'spc.id')
            ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
            ->leftJoin('rona_awal AS ra', 'spra.id_rona_awal', '=', 'ra.id')
            ->where('sp.id_project', $id)
            ->whereNotNull('spc.id')
            ->whereNotNull('spra.id')
            ->orderBy('spra.id', 'asc')
            ->get();

    }

    private function getComponentsGroupByStage($id) {
        $ids = [4,1,2,3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function($model) use($ids) {
            return array_search($model->getKey(),$ids);
        });
        $components = [];
        $components_flat = $this->getComponents($id);
        foreach($stages as $stage) {
            $list = [];
            foreach($components_flat as $c) {
                if ($c->id_project_stage == $stage->id
                || $c->id_project_stage_master == $stage->id) {
                    if (empty($c->name)) {
                        $c->name = $c->name_master;
                    }
                    array_push($list, $c);
                }
            }
            array_push($components, [
                'id_project_stage' => $stage->id,
                'project_stage_name' => $stage->name,
                'components' => $list,
            ]);
        }
        return $components;
    }

    private function getComponents($id) {
        return SubProjectComponent::from('sub_project_components AS spc')
            ->selectRaw('lower(spc.name) as name, lower(c.name) as name_master, c.id as id_component, spc.id_project_stage, c.id_project_stage AS id_project_stage_master')
            ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
            ->leftJoin('components AS c', 'spc.id_component', '=', 'c.id')
            ->where('sp.id_project', $id)
            ->whereNotNull('spc.id')
            ->distinct()
            ->get();
    }
}
