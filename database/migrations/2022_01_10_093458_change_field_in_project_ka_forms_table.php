<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldInProjectKaFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_ka_forms', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->dropColumn('completeness');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_ka_forms', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('completeness')->nullable();
        });
    }
}
