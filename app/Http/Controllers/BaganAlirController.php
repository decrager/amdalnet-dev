<?php

namespace App\Http\Controllers;

use App\Entity\EnvImpactAnalysis;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactIdentificationClone;
use App\Entity\KegiatanLainSekitar;
use App\Entity\Project;
use App\Entity\ProjectStage;
use App\Entity\SignificantImpactFlowchart;
use App\Entity\SubProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaganAlirController extends Controller
{
    public function baganAlirUklUpl(Request $request, $id)
    {
        $getRencanaKegiatan = SubProject::select('id', 'id_project', 'name', 'type')->where('id_project', $id)->get();
        $is_andal = $request->is_andal ? true : false;

        $getKegiatanLainSekitar = KegiatanLainSekitar::whereHas('project', function($q) use($id, $is_andal) {
            $q->where('project_id', $id);
            $q->where('is_andal', $is_andal);
        })->get();

        $getFeedback = DB::table('announcements')
            ->select('feedbacks.concern', 'feedbacks.expectation')
            ->join('feedbacks', 'feedbacks.announcement_id', '=', 'announcements.id')
            ->join('projects', 'projects.id', '=', 'announcements.project_id')
            ->where('projects.id', '=', $id)
            ->get();

        $getRonaAwal = DB::table('sub_project_rona_awals')
            ->select('component_types.name as component_name', 'project_stages.name as stage_name', 'sub_project_rona_awals.name as rona_name')
            ->leftJoin('sub_project_components', 'sub_project_components.id', '=', 'sub_project_rona_awals.id_sub_project_component')
            ->leftJoin('sub_projects', 'sub_projects.id', '=', 'sub_project_components.id_sub_project')
            ->leftJoin('projects', 'projects.id', '=', 'sub_projects.id_project')
            ->leftJoin('project_stages', 'project_stages.id', '=', 'sub_project_components.id_project_stage')
            ->leftJoin('component_types', 'component_types.id', '=', 'sub_project_rona_awals.id_component_type')
            ->where('projects.id', '=', $id)
            ->get();

        $dampakPentingPotensi = ImpactIdentification::select(
            'pc.id_project_stage',
            'pc.name AS component_name',
            'pra.name AS rona_awal_name',
            'ct.name AS change_type_name',
            'is_managed'
        )
            ->leftJoin('sub_project_components AS pc', 'impact_identifications.id_sub_project_component', '=', 'pc.id')
            ->leftJoin('sub_project_rona_awals AS pra', 'impact_identifications.id_sub_project_rona_awal', '=', 'pra.id')
            ->leftJoin('change_types AS ct', 'impact_identifications.id_change_type', '=', 'ct.id')
            ->leftJoin('components AS c', 'pc.id_component', '=', 'c.id')
            ->where('impact_identifications.id_project', $id)
            ->orderBy('impact_identifications.id', 'asc')
            ->get();

        return response()->json([
            'rencana_kegiatan' => $getRencanaKegiatan,
            'kegiatan_lain_sekitar' => $getKegiatanLainSekitar,
            'rona_awal' => $getRonaAwal,
            'feedback' => $getFeedback,
            'dampak_penting_potensi' => $dampakPentingPotensi,
        ]);
    }

    public function baganAlirDampakPenting($id)
    {
        $project = Project::findOrFail($id);
        $data = [
            'Pra Konstruksi' => [],
            'Konstruksi' => [],
            'Operasi' => [],
            'Pasca Operasi' => []
        ];
        
        $impact_identifications = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal', 'is_hypothetical_significant', 'id_sub_project_component', 'id_sub_project_rona_awal')->where('id_project', $id)->whereHas('envImpactAnalysis')->get();

        foreach ($impact_identifications as $imp) {
            if ($imp->projectComponent) {
                $stage_name = $imp->projectComponent->component->stage->name;
                if ($imp->projectRonaAwal) {
                    $ronaAwal = $imp->projectRonaAwal->rona_awal->name;
                    $component = $imp->projectComponent->component->name;
                    $change_type = $imp->id_change_type ? $imp->changeType->name : '';

                    if(!array_key_exists($component, $data[$stage_name])) {
                        $data[$stage_name][$component] = [];
                    }

                    $data[$stage_name][$component][] = [
                        'id_env_impact_analysis' => $imp->envImpactAnalysis->id,
                        'dampak' => $change_type . ' ' . $ronaAwal,
                        'type' => $imp->envImpactAnalysis->impact_type,
                        'parents' => $this->getParents($imp->envImpactAnalysis->child)
                    ];
                } else {
                    continue;
                }
            } else {
                continue;
            }
        }

        return response()->json([
            'title' => $project->project_title,
            'data' => $data
        ]);
    }

    public function baganAlirDampakPentingTable($id)
    {
        $data = [
            'primer' => [],
            'sekunder' => [],
            'tersier' => []
        ];

        $impact_identifications = ImpactIdentificationClone::select('id', 'id_project', 'id_project_component', 'id_change_type', 'id_project_rona_awal', 'is_hypothetical_significant', 'id_sub_project_component', 'id_sub_project_rona_awal')->where('id_project', $id)->whereHas('envImpactAnalysis')->get();

        foreach($impact_identifications as $imp) {
            if ($imp->projectComponent) {
                if ($imp->projectRonaAwal) {
                    $ronaAwal = $imp->projectRonaAwal->rona_awal->name;
                    $change_type = $imp->id_change_type ? $imp->changeType->name : '';

                    if($imp->envImpactAnalysis->impact_type === 'Primer') {
                        $data['primer'][] = ['id' => $imp->envImpactAnalysis->id, 'dampak' => $change_type . ' ' . $ronaAwal];
                    } else if($imp->envImpactAnalysis->impact_type === 'Sekunder') {
                        $data['sekunder'][] = [
                            'id' => $imp->envImpactAnalysis->id,
                            'dampak' => $change_type . ' ' . $ronaAwal,
                            'parents' => $this->getParents($imp->envImpactAnalysis->child, 'table')
                        ];
                    } else if($imp->envImpactAnalysis->impact_type === 'Tersier') {
                        $data['tersier'][] = [
                            'id' => $imp->envImpactAnalysis->id,
                            'dampak' => $change_type . ' ' . $ronaAwal,
                            'parents' => $this->getParents($imp->envImpactAnalysis->child, 'table')
                        ];
                    }
                } else {
                    continue;
                }
            } else {
                continue;
            }
        }

        return response()->json($data);
    }

    public function storeBaganAlirDampakPenting(Request $request, $id)
    {
        $added_relation = $request->addedRelation;
        $deleted_relation = $request->deletedRelation;

        if(count($added_relation) > 0) {
            for($i = 0; $i <= count($added_relation) - 1; $i++) {
                $new_relation = new SignificantImpactFlowchart();
                $new_relation->parent_id = $added_relation[$i]['parentId'];
                $new_relation->child_id = $added_relation[$i]['childId'];
                $new_relation->save();
            }
        }

        if(count($deleted_relation) > 0) {
            for($i = 0; $i <= count($deleted_relation) - 1; $i++) {
                SignificantImpactFlowchart::where([['parent_id', $deleted_relation[$i]['parentId']], ['child_id', $deleted_relation[$i]['childId']]])
                                            ->delete();
            }
        }

        return response()->json(['message' => 'success']);
    }

    private function getEnvImpactAnalysis($id_project, $stages)
    {
        $alphabet_list = 'A';

        $impactIdentifications = ImpactIdentificationClone::select('id', 'id_project', 'id_sub_project_component', 'id_change_type', 'id_sub_project_rona_awal')->where([['id_project', $id_project], ['is_hypothetical_significant', true]])->get();
        $results = [];

        foreach ($stages as $s) {
            $results[] = [
                'id' => $s->id,
                'name' =>  $alphabet_list . '. ' . $s->name,
                'type' => 'title'
            ];

            $total = 0;

            foreach ($impactIdentifications as $imp) {
                $ronaAwal = '';
                $component = '';

                // check stages
                $id_stages = null;

                if ($imp->subProjectComponent) {
                    if ($imp->subProjectComponent->id_project_stage) {
                        $id_stages = $imp->subProjectComponent->id_project_stage;
                    } else {
                        $id_stages = $imp->subProjectComponent->component->id_project_stage;
                    }

                    if ($id_stages == $s->id) {
                        if ($imp->subProjectRonaAwal) {
                            $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                            $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }

                $important_trait = [];

                foreach ($imp->envImpactAnalysis->detail as $det) {
                    $important_trait[] = [
                        'id' => $det->id_important_trait,
                        'description' => $det->importantTrait->description,
                        'desc' => $det->description,
                    ];
                }

                $results[] = [
                    'id' => $imp->id,
                    'type' => 'subtitle',
                    'impact_size' => $imp->envImpactAnalysis->impact_size,
                    'important_trait' => $important_trait,
                ];
                $total++;
            }

            if ($total == 0) {
                array_pop($results);
            } else {
                $alphabet_list++;
            }
        }

        return $results;
    }

    public function evalDampak(Request $request)
    {
        if ($request->lastTime && $request->idProject) {
            $id_project = $request->idProject;
            $time = EnvImpactAnalysis::whereHas('impactIdentification', function ($q) use ($id_project) {
                $q->where('id_project', $id_project);
            })->orderBy('updated_at', 'DESC')->first();

            if ($time) {
                return 'Diperbarui ' . $time->updated_at->locale('id')->diffForHumans();
            } else {
                return null;
            }
        }

        if ($request->idProject) {
            $ids = [4, 1, 2, 3];
            $stages = ProjectStage::select('id', 'name')->get()->sortBy(function ($model) use ($ids) {
                return array_search($model->getKey(), $ids);
            });

            $project = Project::where('id', $request->idProject)->whereHas('impactIdentificationsClone', function ($query) {
                $query->whereHas('envImpactAnalysis');
            })->first();

            if ($project) {
                return $this->getEnvImpactAnalysis($request->idProject, $stages);
            } else {
                return $this->getImpactNotifications($request->idProject, $stages);
            }
        }
    }

    // to do
    private function getImpactNotifications($id, $stages) {
        return [];
    }

    private function getParents($data, $type = 'svg')
    {
        $dampak = [];
        foreach($data as $d) {
            if($d->parent->impactIdentification->projectComponent) {
                if ($d->parent->impactIdentification->projectRonaAwal) {
                    if($type == 'svg') {
                        $ronaAwal = $d->parent->impactIdentification->projectRonaAwal->rona_awal->name;
                        $change_type = $d->parent->impactIdentification->id_change_type ? $d->parent->impactIdentification->changeType->name : '';
                        $dampak[] = $change_type . ' ' . $ronaAwal;
                    } else if($type == 'table') {
                        $dampak[] = [
                            'id' => $d->parent->id,
                            'real_id' => $d->parent->id
                        ];
                    }
                } else {
                    continue;
                }
            } else {
                continue;
            }
        }

        return $dampak;
    }
}
