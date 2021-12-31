<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInFeasibilityTestTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feasibility_test_teams', function (Blueprint $table) {
            $table->integer('id_province_name')->nullable();
            $table->integer('id_district_name')->nullable();

            $table->foreign('id_province_name')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('id_district_name')->references('id')->on('districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feasibility_test_teams', function (Blueprint $table) {
            $table->dropForeign(['id_province_name']);
            $table->dropForeign(['id_district_name']);

            $table->dropColumn('id_province_name');
            $table->dropColumn('id_district_name');
        });
    }
}
