<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFormPeriodToEnvMonitorPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('env_monitor_plans', function (Blueprint $table) {
            $table->text('form')->nullable();
            $table->text('period')->nullable();
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
            $table->dropColumn('form');
            $table->dropColumn('period');
        });
    }
}
