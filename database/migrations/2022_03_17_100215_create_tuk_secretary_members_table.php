<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTukSecretaryMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tuk_secretary_members', function (Blueprint $table) {
            $table->id();
            $table->integer('id_feasibility_test_team')->nullable();
            $table->string('status')->nullable();
            $table->string('nik')->nullable();
            $table->string('nip')->nullable();
            $table->string('name')->nullable();
            $table->string('institution')->nullable();
            $table->string('email')->nullable();
            $table->string('position')->nullable();
            $table->string('phone')->nullable();
            $table->string('decision_number')->nullable();
            $table->string('decision_file')->nullable();
            $table->string('sex')->nullable();
            $table->string('cv')->nullable();
            $table->integer('id_province')->nullable();
            $table->integer('id_district')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();

            $table->foreign('id_feasibility_test_team')->references('id')->on('feasibility_test_teams')->onDelete('cascade');
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
        Schema::dropIfExists('tuk_secretary_members');
    }
}
