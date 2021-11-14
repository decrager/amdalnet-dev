<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestingMeetingInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testing_meeting_invitations', function (Blueprint $table) {
            $table->id();
            $table->integer('id_expert_bank_team_member')->nullable();
            $table->integer('id_testing_meeting')->nullable();
            $table->string('role')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testing_meeting_invitations');
    }
}
