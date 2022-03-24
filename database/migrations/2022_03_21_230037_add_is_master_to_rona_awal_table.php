<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsMasterToRonaAwalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rona_awal', function (Blueprint $table) {
            //
            $table->boolean('is_master')->default(true);
            $table->bigInteger('originator_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rona_awal', function (Blueprint $table) {
            //
            $table->dropColumn('is_master');
            $table->dropColumn('originator_id');
        });
    }
}
