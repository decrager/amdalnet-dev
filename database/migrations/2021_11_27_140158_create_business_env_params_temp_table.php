<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessEnvParamsTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_env_params_temp', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->nullable();
            $table->text('param')->nullable();
            $table->string('condition')->nullable();
            $table->string('unit')->nullable();
            $table->string('doc_req')->nullable();
            $table->string('amdal_type')->nullable();
            $table->string('risk_level')->nullable();
            $table->string('is_param_multisector')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_env_params_temp');
    }
}
