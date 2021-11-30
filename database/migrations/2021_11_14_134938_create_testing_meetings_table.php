<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestingMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testing_meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('id_testing_verification')->nullable();
            $table->date('meeting_date')->nullable();
            $table->integer('id_public_consultation')->nullable();
            $table->time('meeting_time')->nullable();
            $table->string('person_responsible')->nullable();
            $table->string('location')->nullable();
            $table->string('position')->nullable();
            $table->integer('expert_bank_team_id')->nullable();
            $table->string('project_name')->nullable();
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
        Schema::dropIfExists('testing_meetings');
    }
}
