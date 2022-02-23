<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterOssNibsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oss_nibs', function (Blueprint $table) {
            DB::statement('ALTER TABLE oss_nibs ALTER COLUMN json_content TYPE json USING json_content::json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oss_nibs', function (Blueprint $table) {
            DB::statement('ALTER TABLE oss_nibs ALTER COLUMN json_content TYPE text');
        });
    }
}
