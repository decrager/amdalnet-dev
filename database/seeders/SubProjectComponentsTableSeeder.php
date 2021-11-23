<?php

namespace Database\Seeders;

use App\Entity\SubProjectComponent;
use Illuminate\Database\Seeder;

class SubProjectComponentsTableSeeder extends Seeder
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
            SubProjectComponent::create($item);
        }
    }

    private function getData()
    {
        return [
            [
                'id_sub_project' => 1,
                'id_component' => 2,
                'name' => null,
                'id_project_stage' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project' => 1,
                'id_component' => 11,
                'name' => null,
                'id_project_stage' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project' => 1,
                'id_component' => 12,
                'name' => null,
                'id_project_stage' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project' => 1,
                'id_component' => 4,
                'name' => null,
                'id_project_stage' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project' => 1,
                'id_component' => 9,
                'name' => null,
                'id_project_stage' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project' => 1,
                'id_component' => null,
                'name' => 'custom component 1',
                'id_project_stage' => 2,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project' => 1,
                'id_component' => null,
                'name' => 'custom component 2',
                'id_project_stage' => 3,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
            [
                'id_sub_project' => 1,
                'id_component' => 19,
                'name' => null,
                'id_project_stage' => null,
                'description_common' => 'common description',
                'description_specific' => 'specific description',
            ],
        ];
    }
}
