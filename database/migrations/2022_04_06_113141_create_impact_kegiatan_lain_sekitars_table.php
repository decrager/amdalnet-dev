<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImpactKegiatanLainSekitarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impact_kegiatan_lain_sekitars', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_impact_identification');
            $table->bigInteger('id_project_kegiatan_lain_sekitar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('impact_kegiatan_lain_sekitars');
    }
}
