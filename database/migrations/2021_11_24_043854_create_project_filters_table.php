<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_filters', function (Blueprint $table) {
            $table->id();
            $table->integer('id_project');
            $table->boolean('wastewater')->default(0);
            $table->boolean('disposal_wastewater')->default(0);
            $table->boolean('utilization_wastewater')->default(0);
            $table->boolean('high_pollution')->default(0);
            $table->boolean('emission')->default(0);
            $table->boolean('chimney')->default(0);
            $table->boolean('genset')->default(0);
            $table->boolean('high_emission')->default(0);
            $table->boolean('b3')->default(0);
            $table->boolean('collect_b3')->default(0);
            $table->boolean('hoard_b3')->default(0);
            $table->boolean('process_b3')->default(0);
            $table->boolean('utilization_b3')->default(0);
            $table->boolean('dumping_b3')->default(0);
            $table->boolean('tps')->default(0);
            $table->boolean('traffic')->default(0);
            $table->boolean('low_traffic')->default(0);
            $table->boolean('mid_traffic')->default(0);
            $table->boolean('high_traffic')->default(0);
            $table->boolean('nothing')->default(0);
            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade');
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
        Schema::dropIfExists('project_filters');
    }
}
