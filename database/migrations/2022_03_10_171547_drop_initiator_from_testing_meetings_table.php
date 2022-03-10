<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropInitiatorFromTestingMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testing_meetings', function (Blueprint $table) {
            $table->dropColumn('id_initiator');
            $table->dropColumn('person_responsible');
            $table->dropColumn('position');
            $table->dropColumn('project_name');
            $table->dropColumn('id_feasibility_test_team');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('testing_meetings', function (Blueprint $table) {
            $table->integer('id_initiator')->nullable();
            $table->string('person_responsible')->nullable();
            $table->string('position')->nullable();
            $table->string('project_name')->nullable();
            $table->integer('id_feasibility_test_team')->nullable();
        });
    }
}
