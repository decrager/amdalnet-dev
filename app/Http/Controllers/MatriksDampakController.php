<?php

namespace App\Http\Controllers;

use App\Entity\ComponentType;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactIdentificationClone;
use App\Entity\ProjectStage;
use App\Entity\SubProjectComponent;
use App\Entity\SubProjectRonaAwal;
use Illuminate\Http\Request;
use Exception;

class MatriksDampakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTable($id)
    {
        $data = [];
        $rona_awals = $this->getRonaAwals($id);
        $rona_mapping = $this->getRonaMapping($id);
        foreach ($rona_awals as $ra) {
            if ($ra->name == null) {
                $ra->name = $ra->name_master;
            }
            if ($ra->id_component_type == null) {
                $ra->id_component_type = $ra->id_component_type_master;
            }
        }

        $components_by_stage = $this->getComponentsGroupByStage($id);
        $component_ronas = SubProjectRonaAwal::from('sub_project_rona_awals AS spra')
            ->select('spra.id', 'spc.name AS component_name', 'spra.name AS rona_awal_name')
            ->leftJoin('sub_project_components AS spc', 'spra.id_sub_project_component', '=', 'spc.id')
            ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
            ->leftJoin('components AS c', 'spc.id_component', '=', 'c.id')
            ->where('sp.id_project', $id)
            ->get();

        foreach ($components_by_stage as $cstage) {
            $index = 1;
            $item = [];
            $item['type'] = 'stage';
            $item['component_name'] = $cstage['project_stage_name'];
            $item['component_types'] = [];
            array_push($data, $item);
            foreach ($cstage['components'] as $component) {
                $item['type'] = 'component';
                $item['component_name'] = $index . '. ' . $component->name;
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
                            if (strtolower($cr->component_name) == strtolower($component->name)
                            && strtolower($cr->rona_awal_name) == strtolower($ra['name'])) {
                                $ctype[$k] = 'v';
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

    public function getTableDph(Request $request, $id)
    {
        $data = [];
        $rona_awals = $this->getRonaAwals($id);
        $rona_mapping = $this->getRonaMapping($id);
        foreach ($rona_awals as $ra) {
            if ($ra->name == null) {
                $ra->name = $ra->name_master;
            }
            if ($ra->id_component_type == null) {
                $ra->id_component_type = $ra->id_component_type_master;
            }
        }

        $components_by_stage = $this->getComponentsGroupByStage($id);
        $component_ronas = SubProjectRonaAwal::from('sub_project_rona_awals AS spra')
            ->select('spra.id', 'spc.name AS component_name', 'spra.name AS rona_awal_name')
            ->leftJoin('sub_project_components AS spc', 'spra.id_sub_project_component', '=', 'spc.id')
            ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
            ->leftJoin('components AS c', 'spc.id_component', '=', 'c.id')
            ->where('sp.id_project', $id)
            ->get();

        foreach ($components_by_stage as $cstage) {
            $index = 1;
            $item = [];
            $item['type'] = 'stage';
            $item['component_name'] = $cstage['project_stage_name'];
            $item['component_types'] = [];
            array_push($data, $item);
            foreach ($cstage['components'] as $component) {
                $item['type'] = 'component';
                $item['component_name'] = $index . '. ' . $component->name;
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
                            if (strtolower($cr->component_name) == strtolower($component->name)
                            && strtolower($cr->rona_awal_name) == strtolower($ra['name'])) {
                                // check if DPH
                                // select is_hypothetical_significant 
                                // from impact_identifications ii where id_sub_project_rona_awal
                                $dph = null;
                                if($request->isAndal === 'true') {
                                    $dph = ImpactIdentificationClone::select('is_hypothetical_significant')
                                        ->where('id_sub_project_rona_awal', $cr->id)
                                        ->first();
                                } else {
                                    $dph = ImpactIdentification::select('is_hypothetical_significant')
                                        ->where('id_sub_project_rona_awal', $cr->id)
                                        ->first();
                                }
                                if ($dph->is_hypothetical_significant) {
                                    $ctype[$k] = 'DPH';
                                } else if (!$dph->is_hypothetical_significant){
                                    $ctype[$k] = 'DTPH';
                                }
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
                        $ra_key = preg_replace('/[^a-zA-Z0-9_.]/', '_', strtolower($ra->name));
                        if (!in_array($ra->name, $ra_names)) {
                            array_push($ra_list, [
                                'key' => $ra_key,
                                'name' => $ra->name,
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

    private function getRonaAwals($id) {
        return SubProjectRonaAwal::from('sub_project_rona_awals AS spra')
            ->select('spra.id','spra.name','spra.id_component_type',
                'ra.name AS name_master', 'ra.id_component_type AS id_component_type_master')
            ->leftJoin('sub_project_components AS spc', 'spra.id_sub_project_component', '=', 'spc.id')
            ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
            ->leftJoin('rona_awal AS ra', 'spra.id_rona_awal', '=', 'ra.id')
            ->where('sp.id_project', $id)
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
            ->select('spc.name', 'spc.id_project_stage', 'c.id_project_stage AS id_project_stage_master')
            ->leftJoin('sub_projects AS sp', 'spc.id_sub_project', '=', 'sp.id')
            ->leftJoin('components AS c', 'spc.id_component', '=', 'c.id')
            ->where('sp.id_project', $id)
            ->distinct()
            ->get();
    }
}
