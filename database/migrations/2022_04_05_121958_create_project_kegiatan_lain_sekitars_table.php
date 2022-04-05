<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectKegiatanLainSekitarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_kegiatan_lain_sekitars', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('measurement')->nullable();
            $table->string('address')->nullable();
            $table->integer('district_id');
            $table->integer('province_id');
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_kegiatan_lain_sekitars');
    }
}
