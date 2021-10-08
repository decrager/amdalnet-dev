<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDraftingTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_drafting_teams', function (Blueprint $table) {
            $table->id();
            $table->string('id_team');
            $table->string('name')->nullable();
            $table->integer('status')->nullable();
            $table->string('id_applicant')->nullable();
            $table->timestamp('date_input')->nullable();
            $table->string('evidence_letter')->nullable();
            $table->string('id_drafter')->nullable();

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
        Schema::dropIfExists('project_drafting_teams');
    }
}
