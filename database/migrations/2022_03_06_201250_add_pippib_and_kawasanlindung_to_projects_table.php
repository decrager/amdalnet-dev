<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPippibAndKawasanlindungToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('ppib')->nullable();
            $table->string('ppib_file')->nullable();
            $table->string('kawasan_lindung')->nullable();
            $table->string('kawasan_lindung_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('ppib');
            $table->dropColumn('ppib_file');
            $table->dropColumn('kawasan_lindung');
            $table->dropColumn('kawasan_lindung_file');
        });
    }
}
