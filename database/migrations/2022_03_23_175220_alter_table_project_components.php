<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProjectComponents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_components', function (Blueprint $table) {
            //
            $table->text('description')->nullable();
            $table->text('measurement')->nullable();
            $table->dropColumn('name');
            $table->dropColumn('id_project_stage');
            // $table->integer('id_component')->nullable();

            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('id_component')->references('id')->on('components')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_components', function (Blueprint $table) {
            //
            $table->dropColumn('description');
            $table->dropColumn('measurement');
        });
    }
}
