<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignificantImpactFlowchartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('significant_impact_flowcharts', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->integer('child_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('env_impact_analysis')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('env_impact_analysis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('significant_impact_flowcharts');
    }
}
