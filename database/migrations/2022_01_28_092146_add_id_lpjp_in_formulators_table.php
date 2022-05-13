<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdLpjpInFormulatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulators', function (Blueprint $table) {
            $table->integer('id_lpjp')->nullable();
            $table->foreign('id_lpjp')->references('id')->on('lpjp')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulators', function (Blueprint $table) {
            $table->dropForeign(['id_lpjp']);
            $table->dropColumn('id_lpjp');
        });
    }
}
