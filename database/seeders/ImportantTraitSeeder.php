<?php

namespace Database\Seeders;

use App\Entity\ImportantTrait;
use Illuminate\Database\Seeder;

class ImportantTraitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            "Besarnya Jumlah Penduduk yang akan Terkena Dampak Rencana Usaha dan/atau Kegiatan",
            "Luas Wilayah Persebaran Dampak",
            "Lama dan Intensitas Dampak",
            "Banyaknya Komponen Lingkungan Lain Terkena Dampak",
            "Sifat Kumulatif Dampak",
            "Berbalik atau Tidak Berbalik",
            "Kriteria Lain Sesuai Perkembangan Ilmu Pengetahuan dan Teknologi"
        ];

        for($i = 0; $i < count($data); $i++) {
            $trait = new ImportantTrait();
            $trait->description = $data[$i];
            $trait->save();
        }
    }
}
