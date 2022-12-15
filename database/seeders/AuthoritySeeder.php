<?php

namespace Database\Seeders;

use App\Entity\Authority;
use Illuminate\Database\Seeder;

class AuthoritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authorities = [
            [
                'sector' => 'ESDM',
                'project' => 'Semua Kegiatan Migas',
                'authority' => 'Pusat'
            ],
        ];

        foreach($authorities as $authoritys){
            $authority = new Authority();
            $authority->updateOrCreate(
                [
                    'sector' => $authoritys['sector'],
                ],
                [
                    'project' => $authoritys['project'],
                    'authority' => $authoritys['authority'],
                ],
            );
        }
    }
}
