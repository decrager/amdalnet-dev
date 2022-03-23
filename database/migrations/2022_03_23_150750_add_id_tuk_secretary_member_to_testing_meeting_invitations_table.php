<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdTukSecretaryMemberToTestingMeetingInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testing_meeting_invitations', function (Blueprint $table) {
            $table->integer('id_tuk_secretary_member')->nullable();
            $table->foreign('id_tuk_secretary_member')->references('id')->on('tuk_secretary_members')->onDelete('cascade');
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
            $table->dropForeign(['id_tuk_secretary_member']);
            $table->dropColumn('id_tuk_secretary_member');
        });
    }
}
