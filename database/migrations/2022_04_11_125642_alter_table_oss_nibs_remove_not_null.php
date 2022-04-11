<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableOssNibsRemoveNotNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oss_nibs', function (Blueprint $table) {
            DB::statement('ALTER TABLE oss_nibs ALTER COLUMN nib_submit_date DROP NOT NULL');
            DB::statement('ALTER TABLE oss_nibs ALTER COLUMN nib_published_date DROP NOT NULL');
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
            DB::statement('ALTER TABLE oss_nibs ALTER COLUMN nib_submit_date NOT NULL');
            DB::statement('ALTER TABLE oss_nibs ALTER COLUMN nib_published_date NOT NULL');
        });
    }
}
