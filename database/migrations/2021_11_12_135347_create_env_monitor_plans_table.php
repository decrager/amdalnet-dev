<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvMonitorPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('env_monitor_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_impact_identifications')->nullable();
            $table->string('collection_method')->nullable();
            $table->string('location')->nullable();
            $table->string('time_frequent')->nullable();
            $table->integer('executor_institution_id')->nullable();
            $table->integer('supervisor_institution_id')->nullable();
            $table->integer('recipient_institution_id')->nullable();
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
        Schema::dropIfExists('env_monitor_plans');
    }
}
