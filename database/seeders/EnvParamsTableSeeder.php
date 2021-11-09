<?php

namespace Database\Seeders;

use App\Entity\EnvParam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnvParamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = DB::table('kbli_env_params as kb')
            ->selectRaw('kb.id, kb.kbli_id, params.id as id_param,kb.condition,units.id as id_unit, kb.doc_req,kb.amdal_type,kb.risk_level,kb.is_param_multisector')
            ->leftJoin('params', function($join){
                $join->on(DB::raw('Lower(Trim(Regexp_replace(kb.param, \'\s+\', \' \', \'g\')))'), '=', 'params.name' );
            })
            ->leftJoin('units', function($join){
                $join->on(DB::raw('Lower(Trim(Regexp_replace(kb.unit, \'\s+\', \' \', \'g\')))'), '=', 'units.name');
            })
            ->get();

        DB::beginTransaction();
        $inserted = 0;
        $dataArray = json_decode(json_encode($data), true);
        foreach ($dataArray as $row) {
            if (EnvParam::create($row)) {
                $inserted++;
            }
        }
        if ($inserted == count($data)){
            DB::commit();
        }        
    }
}
