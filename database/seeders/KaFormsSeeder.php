<?php

namespace Database\Seeders;

use App\Entity\Comment;
use App\Entity\EnvImpactAnalysis;
use App\Entity\EnvManageApproach;
use App\Entity\EnvManagePlan;
use App\Entity\EnvMonitorPlan;
use App\Entity\ImpactAnalysisDetail;
use App\Entity\ImpactIdentificationClone;
use App\Entity\KaForm;
use App\Entity\Project;
use Illuminate\Database\Seeder;

class KaFormsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $amdal = [
        //     'Komponen Kegiatan',
        //     'Rona Lingkungan Awal',
        //     'Matriks Identifikasi Dampak',
        //     'Dampak Potensial',
        //     'Dampak Penting Hipotetik',
        //     'Metode Studi',
        //     'SHP Peta Tapak Proyek',
        //     'SHP Peta Batas Wilayah Studi',
        //     'SHP Peta Batas Ekologi',
        //     'SHP Peta Batas Sosial'
        // ];

        // for($i = 0; $i < count($amdal); $i++) {
        //     $ka_form = new KaForm();
        //     $ka_form->name = $amdal[$i];
        //     $ka_form->document_type = 'amdal';
        //     $ka_form->save();
        // }
        // EnvManagePlan::truncate();
        // EnvMonitorPlan::truncate();
        // ImpactAnalysisDetail::truncate();
        // EnvImpactAnalysis::truncate();
        // $ia = EnvImpactAnalysis::select('id', 'id_impact_identifications')->get();
        // foreach($ia as $i) {
        //     $imc = ImpactIdentificationClone::find($i->id_impact_identifications);
        //     if($imc === null) {
        //         ImpactAnalysisDetail::where('id_env_impact_analysis', $i->id)->delete();
        //         EnvImpactAnalysis::destroy($ia->id);
        //     }
        // }

        // $rkl = EnvManagePlan::select('id', 'id_impact_identifications')->get();
        // foreach($rkl as $rk) {
        //     $imc = ImpactIdentificationClone::find($rk->id_impact_identifications);
        //     if($imc === null) {
        //         EnvManagePlan::destroy($rk->id);
        //     }
        // }

        // $rpl = EnvMonitorPlan::select('id', 'id_impact_identifications')->get();
        // foreach($rpl as $rp) {
        //     $imc = ImpactIdentificationClone::find($rp->id_impact_identifications);
        //     if($imc === null) {
        //         EnvMonitorPlan::destroy($rp->id);
        //     }
        // }

        // $manage = EnvManageApproach::select('id', 'id_project')->get();
        // foreach($manage as $m) {
        //     $project = Project::find($m->id_project);
        //     if($project === null) {
        //         EnvManageApproach::destroy($m->id);
        //     }
        // }

        // $comments = Comment::whereIn('document_type', ['andal', 'rkl', 'rpl'])->get();
        // foreach($comments as $c) {
        //     $imc = ImpactIdentificationClone::find($c->id_impact_identification);
        //     if($imc === null) {
        //         Comment::destroy($c->id);
        //     }
        // }

        $imp = ImpactIdentificationClone::where('id_project', 165)->get();
        foreach($imp as $i) {
            $ia = EnvImpactAnalysis::where('id_impact_identifications', $i->id)->get();
            foreach($ia as $im) {
                ImpactAnalysisDetail::where('id_env_impact_analysis',$im->id)->delete();
            }
            EnvImpactAnalysis::where('id_impact_identifications', $i->id)->delete();
        }
        ImpactIdentificationClone::where('id_project', 165)->delete();
    }
}
