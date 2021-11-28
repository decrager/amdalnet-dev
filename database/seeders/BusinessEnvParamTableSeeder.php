<?php

namespace Database\Seeders;

use App\Entity\BusinessEnvParam;
use App\Entity\EnvParam;
use App\Entity\Kbli;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessEnvParamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // copy from env_params table
        $EnvParams = EnvParam::all();
        $inserted = 0;
        $EnvParams_array = json_decode(json_encode($EnvParams), true);
        DB::beginTransaction();
        foreach ($EnvParams_array as $EnvParam) {
            try {
                $kbli_field = Kbli::select('id')
                    ->where('parent_id', $EnvParam['kbli_id'])
                    ->orderBy('id', 'desc')
                    ->first();
                if ($kbli_field != null) {
                    if(BusinessEnvParam::create([
                        'id' => $EnvParam['id'],
                        'business_id' => $kbli_field->id,
                        'id_param' => $EnvParam['id_param'],
                        'condition' => $EnvParam['condition'],
                        'id_unit' => $EnvParam['id_unit'],
                        'doc_req' => $EnvParam['doc_req'],
                        'risk_level' => $EnvParam['risk_level'],
                        'amdal_type' => $EnvParam['amdal_type'],
                        'is_param_multisector' => $EnvParam['is_param_multisector'],
                    ])) {
                        $inserted++;
                    }
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
        if ($inserted == count($EnvParams)){
            DB::commit();
        } else {
            DB::rollBack();
        }
    }
}
