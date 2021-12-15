<?php

namespace Database\Seeders;

use App\Entity\Comment;
use App\Entity\EnvImpactAnalysis;
use App\Entity\EnvManagePlan;
use App\Entity\EnvMonitorPlan;
use App\Entity\ImpactAnalysisDetail;
use App\Entity\ImpactIdentificationClone;
use App\Entity\ImpactStudyClone;
use App\Entity\KaForm;
use App\Entity\PotentialImpactEvalClone;
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

        // $imp = ImpactIdentificationClone::where('id_project', 165)->with(['impactStudy','envImpactAnalysis', 'envManagePlan', 'envMonitorPlan', 'comments', 'potentialImpactEvaluation'])->get();

        // foreach($imp as $i) {
        //     if($i->impactStudy) {
        //         ImpactStudyClone::destroy($i->impactStudy->id);
        //     }

        //     $envImpactAnalysis = EnvImpactAnalysis::where('id_impact_identifications', $i->id)->with('detail')->get();
        //     foreach($envImpactAnalysis as $envImpact) {
        //         if($envImpact->detail) {
        //             if($envImpact->detail->first()) {
        //                 foreach($envImpact->detail as $d) {
        //                     ImpactAnalysisDetail::destroy($d->id);
        //                 }
        //             }
        //         }
        //     }
            
        //     if($i->envImpactAnalysis) {
        //         EnvImpactAnalysis::destroy($i->envImpactAnalysis->id);
        //     }

        //     if($i->envManagePlan) {
        //         EnvManagePlan::destroy($i->envManagePlan->id);
        //     }

        //     if($i->envMonitorPlan) {
        //         EnvMonitorPlan::destroy($i->envMonitorPlan->id);
        //     }
            
        //     if($i->comments) {
        //         if($i->comments->first()) {
        //             foreach($i->comments as $c) {
        //                 Comment::destroy($c->id);
        //             }
        //         }
        //     }

        //     if($i->potentialImpactEvaluation) {
        //         if($i->potentialImpactEvaluation->first()) {
        //             foreach($i->potentialImpactEvaluation as $p) {
        //                 PotentialImpactEvalClone::destroy($p->id);
        //             }
        //         }
        //     }
        // }

         ImpactIdentificationClone::where('id_project', 165)->delete();

    }
}
