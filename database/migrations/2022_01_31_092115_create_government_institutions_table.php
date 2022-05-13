<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernmentInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('government_institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('institution_type')->nullable();
            $table->string('email')->nullable();
            $table->integer('id_province')->nullable();
            $table->integer('id_district')->nullable();
            $table->timestamps();

            $table->foreign('id_province')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('id_district')->references('id')->on('districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('government_institutions');
    }
}
