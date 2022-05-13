<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTukToFeasibilityTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feasibility_tests', function (Blueprint $table) {
            $table->integer('id_feasibility_test_team_member')->nullable();
            $table->integer('id_tuk_secretary_member')->nullable();
            $table->string('email')->nullable();

            $table->foreign('id_feasibility_test_team_member')->references('id')->on('feasibility_test_team_members')->onDelete('cascade');
            $table->foreign('id_tuk_secretary_member')->references('id')->on('tuk_secretary_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feasibility_tests', function (Blueprint $table) {
            $table->dropForeign(['id_tuk_secretary_member']);
            $table->dropForeign(['id_feasibility_test_team_member']);

            $table->dropColumn('email');
            $table->dropColumn('id_tuk_secretary_member');
            $table->dropColumn('id_feasibility_test_team_member');
        });
    }
}
