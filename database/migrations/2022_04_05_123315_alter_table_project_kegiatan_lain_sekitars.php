<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProjectKegiatanLainSekitars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_kegiatan_lain_sekitars', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->text('measurement')->nullable()->change();
            $table->integer('kegiatan_lain_sekitar_id');
            $table->dropSoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
