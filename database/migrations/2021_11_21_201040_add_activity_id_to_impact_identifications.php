<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivityIdToImpactIdentifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identifications', function (Blueprint $table) {
            $table->integer('id_project_activity_component')->nullable()->after('id_project_component');
            $table->integer('id_project_activity_rona_awal')->nullable()->after('id_project_rona_awal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('impact_identifications', function (Blueprint $table) {
            $table->dropColumn('id_project_activity_component');
            $table->dropColumn('id_project_activity_rona_awal');
        });
    }
}
