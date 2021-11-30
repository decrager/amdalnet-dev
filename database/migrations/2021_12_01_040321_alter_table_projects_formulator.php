<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterTableProjectsFormulator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            DB::statement('ALTER TABLE projects ALTER COLUMN id_formulator_team TYPE bigint USING (id_formulator_team)::bigint');
            DB::statement('ALTER TABLE projects ALTER COLUMN id_lpjp TYPE bigint USING (id_lpjp)::bigint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
