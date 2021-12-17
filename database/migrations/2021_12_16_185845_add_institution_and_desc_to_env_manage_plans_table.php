<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstitutionAndDescToEnvManagePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('env_manage_plans', function (Blueprint $table) {
            $table->string('executor')->nullable();
            $table->string('supervisor')->nullable();
            $table->string('report_recipient')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('env_manage_plans', function (Blueprint $table) {
            $table->dropColumn('executor');
            $table->dropColumn('supervisor');
            $table->dropColumn('report_recipient');
        });
    }
}
