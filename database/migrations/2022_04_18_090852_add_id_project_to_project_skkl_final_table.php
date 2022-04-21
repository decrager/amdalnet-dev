<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdProjectToProjectSkklFinalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_skkl_finals', function (Blueprint $table) {
            $table->integer('id_project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_skkl_finals', function (Blueprint $table) {
            $table->dropColumn('id_project');
        });
    }
}
