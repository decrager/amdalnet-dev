<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnTestingMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testing_meetings', function (Blueprint $table) {
            $table->dropColumn('id_public_consultation');
            $table->integer('id_initiator');
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
            $table->dropColumn('id_initiator');
            $table->integer('id_public_consultation');
        });
    }
}
