<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubProjectComponentronaawalToImpactIdentificationClones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identification_clones', function (Blueprint $table) {
            $table->integer('id_project_component')->nullable();
            $table->integer('id_project_rona_awal')->nullable();

            $table->foreign('id_project_component')->references('id')->on('project_components')->onDelete('cascade');
            $table->foreign('id_project_rona_awal')->references('id')->on('project_rona_awals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('impact_identification_clones', function (Blueprint $table) {
            $table->dropForeign(['id_project_rona_awal']);
            $table->dropForeign(['id_project_component']);

            $table->dropColumn('id_project_rona_awal');
            $table->dropColumn('id_project_component');
        });
    }
}
