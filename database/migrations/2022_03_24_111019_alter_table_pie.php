<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePie extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('potential_impact_evaluations', function (Blueprint $table) {
            //
            $table->dropForeign('potential_impact_evaluations_id_impact_identification_foreign');
            $table->foreign('id_impact_identification')->references('id')->on('impact_identifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('potential_impact_evaluations', function (Blueprint $table) {
            //
        });
    }
}
