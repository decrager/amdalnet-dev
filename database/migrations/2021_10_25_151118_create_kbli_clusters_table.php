<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKbliClustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kbli_clusters', function (Blueprint $table) {
            $table->id();
            $table->text('list_kbli')->nullable();
            $table->string('cluster_formulir', 250)->nullable();
            $table->string('template_file', 250)->nullable();
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
        Schema::dropIfExists('kbli_clusters');
    }
}
