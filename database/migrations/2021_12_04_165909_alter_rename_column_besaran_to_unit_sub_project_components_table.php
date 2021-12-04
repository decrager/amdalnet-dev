<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterRenameColumnBesaranToUnitSubProjectComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_project_components', function (Blueprint $table) {
            DB::statement('ALTER TABLE sub_project_components RENAME "besaran" TO "unit"');
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
            DB::statement('ALTER TABLE sub_project_components RENAME "unit" TO "besaran"');
        });
    }
}
