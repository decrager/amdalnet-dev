<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdImpactIdentificationToEnvPlanSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('env_plan_sources', function (Blueprint $table) {
            $table->integer('id_impact_identification')->nullable();
            $table->foreign('id_impact_identification')->references('id')->on('impact_identification_clones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('env_plan_sources', function (Blueprint $table) {
            $table->dropForeign(['id_impact_identification']);
            $table->dropColumn('id_impact_identification');
        });
    }
}
