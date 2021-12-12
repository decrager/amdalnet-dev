<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToImpactIdentificationClonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identification_clones', function (Blueprint $table) {
            $table->integer('id_sub_project_component')->nullable();
            $table->integer('id_sub_project_rona_awal')->nullable();
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
            $table->dropColumn('id_sub_project_component');
            $table->dropColumn('id_sub_project_rona_awal');
        });
    }
}
