<?php

namespace App\Http\Controllers;

use App\Entity\ImpactIdentification;
use App\Entity\Project;
use App\Entity\ProjectStage;
use App\Entity\SubProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaganAlirController extends Controller
{
    public function baganAlirUklUpl(Request $request, $id)
    {
        $getRencanaKegiatan = DB::table('sub_projects')
            ->select('sub_projects.name')
            ->leftJoin('projects', 'projects.id', '=', 'sub_projects.id_project')
            ->where('sub_projects.id_project', '=', $id)
            ->where('sub_projects.type', '=', 'utama')
            ->get();

        $getKegiatanLain = DB::table('sub_projects')
            ->select('sub_projects.name')
            ->leftJoin('projects', 'projects.id', '=', 'sub_projects.id_project')
            ->where('sub_projects.id_project', '=', $id)
            ->where('sub_projects.type', '=', 'pendukung')
            ->get();

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
            'kegiatan_lain' => $getKegiatanLain,
            'rona_awal' => $getRonaAwal,
            'feedback' => $getFeedback,
            'dampak_penting_potensi' => $dampakPentingPotensi,
        ]);
    }
}
