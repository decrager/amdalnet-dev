<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubProjectRonaAwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_project_rona_awals', function (Blueprint $table) {
            $table->id();
            $table->integer('id_sub_project');
            $table->integer('id_rona_awal')->nullable();
            $table->string('name')->nullable();
            $table->integer('id_project_stage')->nullable();
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
        Schema::dropIfExists('sub_project_rona_awals');
    }
}
