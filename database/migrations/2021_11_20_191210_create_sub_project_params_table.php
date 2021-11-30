<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubProjectParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_project_params', function (Blueprint $table) {
            $table->id();
            $table->string('param');
            $table->string('scale');
            $table->string('scale_unit');
            $table->string('result');
            $table->integer('id_sub_project')->unsigned();
            $table->foreign('id_sub_project')->references('id')->on('sub_projects')->onDelete('cascade');
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
        Schema::dropIfExists('sub_project_params');
    }
}
