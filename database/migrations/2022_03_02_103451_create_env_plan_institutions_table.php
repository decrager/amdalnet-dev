<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvPlanInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('env_plan_institutions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_impact_identification')->nullable();
            $table->text('executor')->nullable();
            $table->text('supervisor')->nullable();
            $table->text('report_recipient')->nullable();
            $table->timestamps();

            $table->foreign('id_impact_identification')->references('id')->on('impact_identification_clones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('env_plan_institutions');
    }
}
