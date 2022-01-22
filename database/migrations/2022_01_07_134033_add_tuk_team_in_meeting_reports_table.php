<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTukTeamInMeetingReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meeting_reports', function (Blueprint $table) {
            $table->integer('id_feasibility_test_team')->nullable();

            $table->foreign('id_feasibility_test_team')->references('id')->on('feasibility_test_teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meeting_reports', function (Blueprint $table) {
            $table->dropForeign(['id_feasibility_test_team']);
            $table->dropColumn('id_feasibility_test_team');
        });
    }
}
