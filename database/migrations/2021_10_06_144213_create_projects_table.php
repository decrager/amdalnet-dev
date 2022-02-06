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
            $table->string('id_project')->nullable();
            $table->string('project_title')->nullable();
            $table->integer('scale')->nullable();
            $table->string('scale_unit')->nullable();
            $table->string('authority')->nullable();
            $table->string('project_type')->nullable();
            $table->string('sector')->nullable();
            $table->string('description')->nullable();
            $table->integer('id_applicant')->nullable();
            $table->integer('id_prov')->nullable();
            $table->integer('id_district')->nullable();
            $table->string('address')->nullable();
            $table->string('field')->nullable();
            $table->string('location_desc')->nullable();
            $table->string('risk_level')->nullable();
            $table->integer('project_year')->nullable();
            $table->string('map')->nullable();
            $table->string('ktr')->nullable();
            $table->string('type_formulator_team')->nullable();
            $table->integer('map_scale')->nullable();
            $table->string('map_scale_unit')->nullable();
            $table->string('id_formulator_team')->nullable();
            $table->string('announcement_letter')->nullable();
            $table->string('result_risk')->nullable();
            $table->string('kbli')->nullable();
            $table->boolean('published')->default(0);
            $table->string('required_doc')->nullable();
            $table->string('biz_type')->nullable();
            $table->string('pre_agreement')->nullable();
            $table->string('pre_agreement_file')->nullable();
            $table->string('id_lpjp')->nullable();

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
