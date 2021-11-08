<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectRonaAwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_rona_awals', function (Blueprint $table) {
            $table->id();
            $table->string('id_project');
            $table->string('id_rona_awal')->nullable();
            $table->string('name')->nullable();
            $table->integer('id_component_type')->nullable();
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
        Schema::dropIfExists('project_rona_awals');
    }
}
