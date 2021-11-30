<?php

namespace Database\Seeders;

use App\Entity\Business;
use App\Entity\Kbli;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // copy from kblis table
        $kblis = Kbli::all();
        $inserted = 0;
        $kblis_array = json_decode(json_encode($kblis), true);
        DB::beginTransaction();
        foreach ($kblis_array as $kbli) {
            if(Business::create($kbli)) {
                $inserted++;
            }
        }
        if ($inserted == count($kblis)){
            DB::commit();
        } else {
            DB::rollBack();
        }
    }
}
