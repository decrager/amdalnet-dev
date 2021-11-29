<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImpactIdentificationClonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impact_identification_clones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_impact_identification')->nullable();
            $table->integer('id_project')->nullable();
            $table->integer('id_unit')->nullable();
            $table->integer('id_change_type')->nullable();
            $table->float('nominal')->nullable();
            $table->text('potential_impact_evaluation')->nullable();
            $table->boolean('is_hypothetical_significant')->nullable();
            $table->boolean('is_managed')->nullable();
            $table->text('initial_study_plan')->nullable();
            $table->string('study_location')->nullable();
            $table->integer('study_length_year')->nullable();
            $table->integer('study_length_month')->nullable();
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
        Schema::dropIfExists('impact_identification_clones');
    }
}
