<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('env_params', function (Blueprint $table) {
            $table->id();
            $table->integer('kbli_id')->nullable();
            $table->integer('id_param')->nullable();
            $table->string('condition')->nullable();
            $table->integer('id_unit')->nullable();
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
        Schema::dropIfExists('env_params');
    }
}
