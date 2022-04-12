<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAndalToProjectKegiatanLainSekitars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_kegiatan_lain_sekitars', function (Blueprint $table) {
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
        Schema::table('project_kegiatan_lain_sekitars', function (Blueprint $table) {
            //
            $table->dropColumn('is_andal');
        });
    }
}
