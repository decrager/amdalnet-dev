<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataFileToImpactIdentificationClonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identification_clones', function (Blueprint $table) {
            $table->string('data_file')->nullable();
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
            $table->dropColumn('data_file');
        });
    }
}
