<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInitiatorsAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('initiators', function (Blueprint $table) {
            $table->string('agency_type')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('pic_role')->nullable();
            $table->string('logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('initiators', function (Blueprint $table) {
            $table->dropColumn('agency_type');
            $table->dropColumn('province');
            $table->dropColumn('district');
            $table->dropColumn('pic_role');
            $table->dropColumn('logo');
        });
    }
}
