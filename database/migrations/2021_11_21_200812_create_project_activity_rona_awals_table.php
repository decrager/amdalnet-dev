<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectActivityRonaAwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_activity_rona_awals', function (Blueprint $table) {
            $table->id();
            $table->integer('id_project_activity');
            $table->integer('id_rona_awal')->nullable();
            $table->string('name')->nullable();
            $table->integer('id_component_type')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('project_activity_rona_awals');
    }
}
