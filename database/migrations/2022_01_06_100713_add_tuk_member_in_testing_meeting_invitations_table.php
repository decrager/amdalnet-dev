<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTukMemberInTestingMeetingInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testing_meeting_invitations', function (Blueprint $table) {
            $table->integer('id_feasibility_test_team_member')->nullable();

            $table->foreign('id_feasibility_test_team_member')->references('id')->on('feasibility_test_team_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('testing_meeting_invitations', function (Blueprint $table) {
            $table->dropForeign(['id_feasibility_test_team_member']);
            $table->dropColumn('id_feasibility_test_team_member');
        });
    }
}
