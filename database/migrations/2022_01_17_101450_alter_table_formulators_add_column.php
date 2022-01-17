<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableFormulatorsAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulators', function (Blueprint $table) {
            $table->string('nik')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->text('address')->nullable();
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
            $table->dropColumn('nik');
            $table->dropColumn('province');
            $table->dropColumn('district');
            $table->dropColumn('address');
        });
    }
}
