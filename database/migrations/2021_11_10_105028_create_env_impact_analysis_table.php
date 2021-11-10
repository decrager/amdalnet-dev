<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvImpactAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('env_impact_analysis', function (Blueprint $table) {
            $table->id();
            $table->integer('id_impact_identifications')->nullable();
            $table->string('impact_size')->nullable();
            $table->string('important_trait')->nullable();
            $table->string('impact_eval_result')->nullable();
            $table->text('response')->nullable();
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
        Schema::dropIfExists('env_impact_analysis');
    }
}
