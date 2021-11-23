<?php

namespace Database\Seeders;

use App\Entity\SubProject;
use Illuminate\Database\Seeder;

class SubProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'kbli' => '41011',
                'name' => 'Sub Project 1 (utama)',
                'result' => 'Result Sub Project 1',
                'biz_type' => 'Biz Type Sub Project 1',
                'type' => 'utama',
                'id_project' => 61,
            ],
            [
                'kbli' => '41011',
                'name' => 'Sub Project 2 (utama)',
                'result' => 'Result Sub Project 2',
                'biz_type' => 'Biz Type Sub Project 2',
                'type' => 'utama',
                'id_project' => 61,
            ],
            [
                'kbli' => '41011',
                'name' => 'Sub Project 3 (utama)',
                'result' => 'Result Sub Project 3',
                'biz_type' => 'Biz Type Sub Project 3',
                'type' => 'utama',
                'id_project' => 61,
            ],
            [
                'kbli' => '41011',
                'name' => 'Sub Project 4 (pendukung)',
                'result' => 'Result Sub Project 4',
                'biz_type' => 'Biz Type Sub Project 4',
                'type' => 'pendukung',
                'id_project' => 61,
            ],
            [
                'kbli' => '41011',
                'name' => 'Sub Project 5 (pendukung)',
                'result' => 'Result Sub Project 5',
                'biz_type' => 'Biz Type Sub Project 5',
                'type' => 'pendukung',
                'id_project' => 61,
            ],
            [
                'kbli' => '41011',
                'name' => 'Sub Project 6 (pendukung)',
                'result' => 'Result Sub Project 6',
                'biz_type' => 'Biz Type Sub Project 6',
                'type' => 'pendukung',
                'id_project' => 61,
            ],
        ];
        foreach ($data as $item) {
            $existing = SubProject::select('id')
                ->where('name', $item['name'])
                ->get();
            if (count($existing) == 0) {
                SubProject::create($item);
            }
        }
    }
}
