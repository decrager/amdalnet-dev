<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeasibilityTestTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feasibility_test_teams', function (Blueprint $table) {
            $table->id();
            $table->string('authority')->nullable();
            $table->string('assignment_number')->nullable();
            $table->string('assignment_file')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('id_province')->nullable();
            $table->integer('id_district')->nullable();
            $table->text('address')->nullable();
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
        Schema::dropIfExists('feasibility_test_teams');
    }
}
