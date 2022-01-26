<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdparamIdunitToBusinessEnvParamsTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_env_params_temp', function (Blueprint $table) {
            $table->dropColumn('param');
            $table->dropColumn('unit');
            $table->integer('id_unit')->nullable();
            $table->integer('id_param')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_env_params_temp', function (Blueprint $table) {            
            $table->text('param')->nullable();
            $table->string('unit')->nullable();
            $table->dropColumn('id_param');
            $table->dropColumn('id_unit');
        });
    }
}
