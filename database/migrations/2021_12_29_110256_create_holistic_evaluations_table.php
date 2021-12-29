<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolisticEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holistic_evaluations', function (Blueprint $table) {
            $table->id();
            $table->integer('id_project')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();

            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holistic_evaluations');
    }
}
