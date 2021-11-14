<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestingVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testing_verifications', function (Blueprint $table) {
            $table->id();
            $table->integer('id_project')->nullable();
            $table->string('approved_by_position')->nullable();
            $table->string('approved_in')->nullable();
            $table->date('approved_date')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->string('checked_in')->nullable();
            $table->date('checked_date')->nullable();
            $table->date('checked_by')->nullable();
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
        Schema::dropIfExists('testing_verifications');
    }
}
