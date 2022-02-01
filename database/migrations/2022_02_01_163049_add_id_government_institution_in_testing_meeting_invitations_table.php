<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdGovernmentInstitutionInTestingMeetingInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testing_meeting_invitations', function (Blueprint $table) {
            $table->string('institution')->nullable();
            $table->integer('id_government_institution')->nullable();
            $table->foreign('id_government_institution')->references('id')->on('government_institutions')->onDelete('cascade');
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
            $table->dropForeign(['id_government_institution']);
            $table->dropColumn('id_government_institution');
            $table->dropColumn('institution');
        });
    }
}
