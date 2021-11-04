<?php

namespace Database\Seeders;

use App\Entity\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $distinct_units = DB::table(function($query){
            $query->selectRaw('Lower(Trim(Regexp_replace(unit, \'\s+\', \' \', \'g\'))) AS l , Trim(Regexp_replace(unit, \'\s+\', \' \', \'g\')) AS o')
                ->from('kbli_env_params')
                ->distinct();
        },'tb')
        ->select('tb.l as unit_name')
        ->distinct()
        ->orderBy('tb.l', 'asc')->get();

        foreach ($distinct_units as $unit){
            if (!empty($unit->unit_name)){
                Unit::create([
                    'name' => $unit->unit_name,
                ]);
            }
        }
    }
}
