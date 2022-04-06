<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAndalToSubProjectComponents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_project_components', function (Blueprint $table) {
            //
            $table->boolean('is_andal')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_project_components', function (Blueprint $table) {
            //
            $table->dropColumn('is_andal');
        });
    }
}
