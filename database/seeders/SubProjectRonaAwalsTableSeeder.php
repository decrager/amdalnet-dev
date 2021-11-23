<?php

namespace Database\Seeders;

use App\Entity\SubProjectRonaAwal;
use Illuminate\Database\Seeder;

class SubProjectRonaAwalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->getData();
        foreach ($data as $item) {
            SubProjectRonaAwal::create($item);
        }
    }

    private function getData()
    {
        return [
            [
                'id_sub_project_component' => 1,
                'id_rona_awal' => 35,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project_component' => 1,
                'id_rona_awal' => 17,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project_component' => 1,
                'id_rona_awal' => 2,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project_component' => 1,
                'id_rona_awal' => 26,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project_component' => 2,
                'id_rona_awal' => 31,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project_component' => 2,
                'id_rona_awal' => 18,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project_component' => 2,
                'id_rona_awal' => 6,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project_component' => 3,
                'id_rona_awal' => 34,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project_component' => 3,
                'id_rona_awal' => 13,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project_component' => 4,
                'id_rona_awal' => 28,
                'name' => null,
                'id_component_type' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
        ];
    }
}
