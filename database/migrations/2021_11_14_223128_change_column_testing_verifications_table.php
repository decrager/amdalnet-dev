<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTestingVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testing_verifications', function (Blueprint $table) {
            DB::statement('ALTER TABLE testing_verifications ALTER COLUMN checked_by TYPE varchar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('testing_verifications', function (Blueprint $table) {
            DB::statement('ALTER TABLE testing_verifications ALTER COLUMN checked_by TYPE date');
        });
    }
}
