<?php

namespace Database\Seeders;

use App\Entity\Formulator;
use App\Entity\Lpjp;
use Illuminate\Database\Seeder;

class FormulatorCSVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvData = fopen(base_path('database/csv/csv-formulators-3feb2023.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 1000, ';')) !== false) {
            if (!$transRow) {
                Formulator::updateOrCreate([
                    'cert_no'           => $data['1'],
                    'reg_no'            => $data['4'],
                ], [
                    'name'              => $data['0'],
                    'date_start'        => $data['2'],
                    'date_end'          => $data['3'],
                    'membership_status' => $data['5'],
                    'id_lsp'            => $data['6'],
                    'id_lpjp'           => !empty($data['7']) ? (Lpjp::where('reg_no', $data['7'])->first(['id'])->id ?? null) : null,
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
