<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterColumnIdImpactIdtNotNullOnEnvMonitorPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('env_monitor_plans', function (Blueprint $table) {
            DB::statement('ALTER TABLE env_monitor_plans ALTER COLUMN id_impact_identifications SET NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('env_monitor_plans', function (Blueprint $table) {
            DB::statement('ALTER TABLE env_monitor_plans ALTER COLUMN id_impact_identifications DROP NOT NULL');
        });
    }
}
