<?php

namespace Database\Seeders;

use App\Entity\MasterPotentialImpactEvaluationParam;
use Illuminate\Database\Seeder;

class MasterPotentialImpactEvaluationParamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $pie = new MasterPotentialImpactEvaluationParam();
        $pie->name = "A. Besaran rencana Usaha dan/atau Kegiatan";
        $pie->description = "(yang menyebabkan dampak tersebut dan rencana pengelolaan lingkungan awal yang menjadi bagian rencana Usaha dan/atau Kegiatan untuk menanggulangi dampak)";
        $pie->save();

        $pie = new MasterPotentialImpactEvaluationParam();
        $pie->name = "B. Kondisi rona lingkungan";
        $pie->description = "(yang ada termasuk kemampuan mendukung Usaha dan/atau Kegiatan tersebut atau tidak)";
        $pie->save();

        $pie = new MasterPotentialImpactEvaluationParam();
        $pie->name = "C. Pengaruh rencana Usaha dan/atau Kegiatan";
        $pie->description = "(terhadap kondisi Usaha dan/atau Kegiatan lain di sekitar lokasi)";
        $pie->save();

        $pie = new MasterPotentialImpactEvaluationParam();
        $pie->name = "D. Intensitas perhatian masyarakat";
        $pie->description = "(terhadap rencana Usaha dan/atau Kegiatan, baik harapan, kekhawatiran, persetujuan atau penolakan terhadap rencana Usaha dan/atau Kegiatan)";
        $pie->save();

        $pie = new MasterPotentialImpactEvaluationParam();
        $pie->name = "E. Kesimpulan";
        $pie->description = "";
        $pie->save();


    }
}
