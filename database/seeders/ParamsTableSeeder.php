<?php

namespace Database\Seeders;

use App\Entity\Param;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $distinct_params = DB::table(function($query){
            $query->selectRaw('Lower(Trim(Regexp_replace(param, \'\s+\', \' \', \'g\'))) AS l , Trim(Regexp_replace(param, \'\s+\', \' \', \'g\')) AS o')
                ->from('kbli_env_params')
                ->distinct();
        },'tb')
        ->select('tb.l as param_name')
        ->distinct()
        ->orderBy('tb.l', 'asc')->get();

        foreach ($distinct_params as $param){
            if (!empty($param->param_name)){
                Param::create([
                    'name' => $param->param_name,
                ]);
            }
        }
    }
}
