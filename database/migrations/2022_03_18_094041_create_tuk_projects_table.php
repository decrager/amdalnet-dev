<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTukProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tuk_projects', function (Blueprint $table) {
            $table->id();
            $table->integer('id_project')->nullable();
            $table->integer('id_feasibility_test_team_member')->nullable();
            $table->integer('id_tuk_secretary_member')->nullable();
            $table->string('role')->nullable();
            $table->timestamps();

            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('id_feasibility_test_team_member')->references('id')->on('feasibility_test_team_members')->onDelete('cascade');
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
        Schema::dropIfExists('tuk_projects');
    }
}
