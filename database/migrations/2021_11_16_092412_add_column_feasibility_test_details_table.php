<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFeasibilityTestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feasibility_test_details', function (Blueprint $table) {
            $table->integer('id_eligibility_criteria')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feasibility_test_details', function (Blueprint $table) {
            $table->dropColumn('id_eligibility_criteria');
        });
    }
}
