<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sops', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_component')->nullable();
            $table->smallInteger('id_rona_awal')->nullable();
            $table->text('mgmt_form')->nullable();
            $table->string('mgmt_period', 100)->nullable();
            $table->text('monitoring_form')->nullable();
            $table->smallInteger('monitoring_time')->nullable();
            $table->smallInteger('monitoring_freq')->nullable();
            $table->string('monitoring_date_field', 50)->nullable();
            $table->string('name', 150)->nullable();
            $table->string('impact', 50)->nullable();
            $table->string('other_impact', 100)->nullable();
            $table->string('monitoring_period', 50)->nullable();
            $table->string('impact_quantity', 150)->nullable();
            $table->string('code', 50)->nullable();
            $table->timestamp('effective_date')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('sops');
    }
}
