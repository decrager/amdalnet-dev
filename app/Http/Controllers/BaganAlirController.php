<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaganAlirController extends Controller
{
    public function baganAlirUklUpl($id)
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

        $getFeedbackConcern = DB::table('announcements')
            ->select('feedbacks.concern')
            ->leftJoin('feedbacks', 'feedbacks.announcement_id', '=', 'announcements.id')
            ->leftJoin('projects', 'projects.id', '=', 'announcements.project_id')
            ->where('projects.id', '=', $id)
            ->whereNotNull('feedbacks.concern')
            ->get();

        $getFeedbackExpectation = DB::table('announcements')
            ->select('feedbacks.expectation')
            ->leftJoin('feedbacks', 'feedbacks.announcement_id', '=', 'announcements.id')
            ->leftJoin('projects', 'projects.id', '=', 'announcements.project_id')
            ->where('projects.id', '=', $id)
            ->whereNotNull('feedbacks.expectation')
            ->get();


        $getRonaAwal = DB::table('sub_projects')
            ->select('rona_awal.name')
            ->join('sub_project_components', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->join('sub_project_rona_awals', 'sub_project_rona_awals.id_sub_project_component', '=', 'sub_project_components.id')
            ->join('projects', 'projects.id', '=', 'sub_projects.id_project')
            ->join('rona_awal', 'rona_awal.id', '=', 'sub_project_rona_awals.id_rona_awal')
            ->where('projects.id', '=', $id)
            ->get();

        $getDampakPentingHipotetik = DB::table('impact_studies')
            ->select(
                'project_stages.name',
                'impact_identifications.potential_impact_evaluation',
                'impact_identifications.is_hypothetical_significant'
            )
            ->selectRaw('ROW_NUMBER () OVER (ORDER BY impact_studies.id) as number')
            ->leftJoin('impact_identifications', 'impact_studies.id_impact_identification', '=', 'impact_identifications.id')
            ->leftJoin('sub_project_components', 'sub_project_components.id', '=', 'impact_identifications.id_sub_project_component')
            ->leftJoin('project_stages', 'project_stages.id', '=', 'sub_project_components.id_project_stage')
            ->leftJoin('projects', 'projects.id', '=', 'impact_identifications.id_project')
            ->where('projects.id', '=', $id)
            ->where('impact_identifications.is_hypothetical_significant', '=', 'true')
            ->get();

        return response()->json([
            'rencana_kegiatan' => $getRencanaKegiatan,
            'kegiatan_lain' => $getKegiatanLain,
            'rona_awal' => $getRonaAwal,
            'feedback_concern' => $getFeedbackConcern,
            'feedback_expectation' => $getFeedbackExpectation,
            'dampak_penting_hipotetik' => $getDampakPentingHipotetik,
        ]);
    }
}
