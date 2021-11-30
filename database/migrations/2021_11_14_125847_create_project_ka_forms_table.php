<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectKaFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_ka_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('id_testing_verification')->nullable();
            $table->integer('id_ka_form')->nullable();
            $table->string('completeness')->nullable();
            $table->string('suitability')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('project_ka_forms');
    }
}
