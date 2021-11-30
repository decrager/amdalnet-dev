<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotentialImpactEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potential_impact_evaluations', function (Blueprint $table) {
            $table->id();
            $table->integer('id_impact_identification')->unsigned();
            $table->foreign('id_impact_identification')->references('id')->on('impact_identifications');
            $table->integer('id_pie_param')->unsigned();
            $table->foreign('id_pie_param')->references('id')->on('master_potential_impact_evaluation_params');
            $table->text('text')->nullable();
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
        Schema::dropIfExists('potential_impact_evaluations');
    }
}
