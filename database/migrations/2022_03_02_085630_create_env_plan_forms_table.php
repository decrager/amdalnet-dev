<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvPlanFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('env_plan_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('id_env_manage_plan')->nullable();
            $table->integer('id_env_monitor_plan')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('id_env_manage_plan')->references('id')->on('env_manage_plans')->onDelete('cascade');
            $table->foreign('id_env_monitor_plan')->references('id')->on('env_monitor_plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('env_plan_forms');
    }
}
