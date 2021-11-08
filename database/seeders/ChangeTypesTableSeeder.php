<?php

namespace Database\Seeders;

use App\Entity\ChangeType;
use Illuminate\Database\Seeder;

class ChangeTypesTableSeeder extends Seeder
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
                'name' => 'Perubahan'
            ],
            [
                'name' => 'Peningkatan'
            ],
            [
                'name' => 'Penurunan'
            ],
            [
                'name' => 'Gangguan'
            ],
        ];

        foreach ($data as $item) {
            $existing = ChangeType::select('id')
                ->where('name', $item['name'])
                ->get();
            if (count($existing) == 0) {
                ChangeType::create($item);
            }
        }
    }
}
