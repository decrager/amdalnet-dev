<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImpactStudyClonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impact_study_clones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_impact_identification_clone')->nullable();
            $table->integer('id_project')->nullable();
            $table->text('forecast_method')->nullable();
            $table->text('required_information')->nullable();
            $table->text('data_gathering_method')->nullable();
            $table->text('analysis_method')->nullable();
            $table->text('evaluation_method')->nullable();
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
        Schema::dropIfExists('impact_study_clones');
    }
}
