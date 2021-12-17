<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeInImpactIdentificationClonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identification_clones', function (Blueprint $table) {
            $table->foreign('id_impact_identification')->references('id')->on('impact_identifications')->onDelete('cascade');
            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('impact_identification_clones', function (Blueprint $table) {
            $table->dropForeign(['id_impact_identification']);
            $table->dropForeign(['id_project']);
        });
    }
}
