<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicSptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_spt', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('role')->nullable();
            $table->string('nik')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('comment')->nullable();
            $table->text('worries')->nullable();
            $table->text('hope')->nullable();
            $table->integer('relevance')->nullable();
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
        Schema::dropIfExists('public_spt');
    }
}
