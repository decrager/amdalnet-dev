<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvManagePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('env_manage_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_impact_identifications')->nullable();
            $table->string('success_indicator')->nullable();
            $table->string('form')->nullable();
            $table->string('location')->nullable();
            $table->string('period')->nullable();
            $table->string('institution')->nullable();
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
        Schema::dropIfExists('env_manage_plans');
    }
}
