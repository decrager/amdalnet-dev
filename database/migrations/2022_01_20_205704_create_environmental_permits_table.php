<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvironmentalPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('environmental_permits', function (Blueprint $table) {
            $table->id();
            $table->string('pemarkasa_name')->nullable();
            $table->enum('authority', ['semua', 'pusat', 'provinsi', 'kab/kota']);
            $table->text('kegiatan_name')->nullable();
            $table->string('sk_number')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('publisher')->nullable();
            $table->string('file')->nullable();
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
        Schema::dropIfExists('environmental_permits');
    }
}
