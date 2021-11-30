<?php

namespace Database\Seeders;

use App\Entity\EligibilityCriteria;
use Illuminate\Database\Seeder;

class EligibilityCriterieasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            "<p>Kesesuaian lokasi rencana Usaha dan/atau Kegiatan dengan rencana tata ruang dan ketentuan peraturan perundang-undangan yang mengatur terkait dengan pemanfaatan ruang</p>",
            "<p>Kesesuaian lokasi rencana Usaha dan/atau Kegiatan dengan kebijakan di bidang Perlindungan dan Pengelolaan Lingkugan Hidup serta sumber daya alam yang diatur dalam peraturan perundang-undangan</p>",
            "<p>Rencana Usaha dan/atau Kegiatan tidak mengganggu ...</p>",
            "<p>Prakiraan secara cermat mengenai besaran dan sifat ... ekonomi, budaya, tata ruang, dan kesehatan masyarakat ... pasca operasi Usaha dan/atau Kegiatan</p>",
            "<p>Hasil evaluasi secara holistik terhadap seluruh dampak ... saling mempengaruhi sehingga diketahui perimbangan ... bersifat negatif</p>",
            "<p>Kemampuan penanggung jawab Usaha dan/atau Kegiatan ... dalam menanggulangi Dampak Penting negatif yang akan ... direncanakan dengan pendekatan teknologi, sosial, dan kelembagaan</p>",
            "<p>Rencana Usaha dan/atau Kegiatan tidak mengganggu nilai-nilai sosial atau pandangan masyarakat (emic view)</p>",
            "<p>Rencana Usaha dan/atau Kegiatan tidak akan mempengaruhi dan/atau mengganggu ensitas ekologis yang merupakan:</p>
            <br>
            <ol>
                <li>Entitas dan/atau spesies kunci (key species)</li>
                <li>Memiliki nilai penting secara ekologis (ecological importance)</li>
                <li>Memiliki nilai penting secara ekonomi (economic importance) dan/atau</li>
                <li>Memiliki nilai penting secara ilmiah (scientific importance)</li>
            </ol>",
            "<p>Rencana Usaha dan/atau Kegiatan tidak menimbulkan gangguan terhadap Usaha dan/atau Kegiatan yang telah berada di sekitar rencana lokasi Usaha dan/atau Kegiatan</p>",
            "<p>Tidak dilampauinya daya dukung dan daya tampung Lingkungan Hidup dari lokasi rencana Usaha dan/atau Kegiatan, dalam hal terdapat perhitungan daya dukung dan daya tampung Lingkungan Hidup dimaksud</p>"
        ];

        for($i = 0; $i < count($data); $i++) {
            $criteria = new EligibilityCriteria();
            $criteria->description = $data[$i];
            $criteria->save();
        }
    }
}
