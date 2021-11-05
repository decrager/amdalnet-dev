<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePertekKbliClustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pertek_kbli_clusters', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kbli_cluster');
            $table->smallInteger('id_pertek_type');
            $table->string('pertek_file', 150);
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
        Schema::dropIfExists('pertek_kbli_clusters');
    }
}
