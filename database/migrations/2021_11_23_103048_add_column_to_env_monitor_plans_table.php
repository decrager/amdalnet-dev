<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColumnToEnvMonitorPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('env_monitor_plans', function (Blueprint $table) {
            $table->text('indicator')->nullable();
            $table->text('impact_source')->nullable();
            $table->text('executor')->nullable();
            $table->text('supervisor')->nullable();
            $table->text('report_recipient')->nullable();
            $table->dropColumn('executor_institution_id');
            $table->dropColumn('supervisor_institution_id');
            $table->dropColumn('recipient_institution_id');
            DB::statement('ALTER TABLE env_monitor_plans ALTER COLUMN collection_method TYPE text');
            DB::statement('ALTER TABLE env_monitor_plans ALTER COLUMN location TYPE text');
            DB::statement('ALTER TABLE env_monitor_plans ALTER COLUMN time_frequent TYPE text');
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
            $table->dropColumn('indicator');
            $table->dropColumn('impact_source');
            $table->dropColumn('executor');
            $table->dropColumn('supervisor');
            $table->dropColumn('report_recipient');
            $table->integer('executor_institution_id')->nullable();
            $table->integer('supervisor_institution_id')->nullable();
            $table->integer('recipient_institution_id')->nullable();
            DB::statement('ALTER TABLE env_monitor_plans ALTER COLUMN collection_method TYPE varchar');
            DB::statement('ALTER TABLE env_monitor_plans ALTER COLUMN location TYPE varchar');
            DB::statement('ALTER TABLE env_monitor_plans ALTER COLUMN time_frequent TYPE varchar');
        });
    }
}
