<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOssRisksToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('oss_risk')->nullable();
            $table->string('oss_authority')->nullable();
            $table->string('oss_area')->nullable();
            $table->string('oss_invest_status')->nullable();
            $table->string('oss_required_doc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('oss_risk');
            $table->dropColumn('oss_authority');
            $table->dropColumn('oss_area');
            $table->dropColumn('oss_invest_status');
            $table->dropColumn('oss_required_doc');
        });
    }
}
