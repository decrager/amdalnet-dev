<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubProjectComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_project_components', function (Blueprint $table) {
            $table->id();
            $table->integer('id_sub_project');
            $table->integer('id_component')->nullable();
            $table->string('name')->nullable();
            $table->integer('id_project_stage')->nullable();
            $table->string('description_common')->nullable();
            $table->string('description_specific')->nullable();
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
        Schema::dropIfExists('sub_project_components');
    }
}
