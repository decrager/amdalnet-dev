<?php

namespace Database\Seeders;

use App\Entity\KaForm;
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
        $amdal = [
            'Komponen Kegiatan',
            'Rona Lingkungan Awal',
            'Matriks Identifikasi Dampak',
            'Dampak Potensial',
            'Dampak Penting Hipotetik',
            'Metode Studi',
            'SHP Peta Tapak Proyek',
            'SHP Peta Batas Wilayah Studi',
            'SHP Peta Batas Ekologi',
            'SHP Peta Batas Sosial'
        ];

        for($i = 0; $i < count($amdal); $i++) {
            $ka_form = new KaForm();
            $ka_form->name = $amdal[$i];
            $ka_form->document_type = 'amdal';
            $ka_form->save();
        }
    }
}
