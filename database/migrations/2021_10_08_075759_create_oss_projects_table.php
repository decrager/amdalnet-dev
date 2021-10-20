<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOssProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oss_projects', function (Blueprint $table) {
            $table->id();
            $table->string('nib');
            $table->string('applicant_name')->nullable();
            $table->string('no_id_user_process')->nullable();
            $table->string('id_project_oss')->nullable();
            $table->string('biz_scale')->nullable();
            $table->string('risk_scale')->nullable();
            $table->string('project_desc')->nullable();
            $table->string('biz_desc')->nullable();
            $table->string('project_name')->nullable();
            $table->string('kbli')->nullable();
            $table->bigInteger('land_area')->nullable();
            $table->string('land_area_unit')->nullable();
            $table->string('project_address')->nullable();
            $table->string('project_scale')->nullable();
            $table->string('project_scale_unit')->nullable();
            $table->string('document_code')->nullable();
            $table->string('document_name')->nullable();
            $table->string('authority')->nullable();
            $table->json('json_content')->nullable();
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
        Schema::dropIfExists('oss_projects');
    }
}
