<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('id_project');
            $table->string('project_title');
            $table->integer('scale');
            $table->string('scale_unit');
            $table->string('authority');
            $table->string('project_type');
            $table->string('description');
            $table->string('id_applicant');
            $table->integer('id_prov');
            $table->integer('id_district');
            $table->string('address');
            $table->string('field');
            $table->string('location_desc');
            $table->string('document_type');
            $table->integer('project_year');
            $table->string('map');
            $table->integer('map_scale');
            $table->string('map_scale_unit');
            $table->string('id_drafting_team');
            $table->string('announcement_letter');
            $table->string('kbli');
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
        Schema::dropIfExists('projects');
    }
}
