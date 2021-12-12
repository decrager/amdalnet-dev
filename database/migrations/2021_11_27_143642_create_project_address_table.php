<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_address', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_project')->unsigned();
            $table->string('prov')->nullable();
            $table->string('district')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_address');
    }
}
