<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLspTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsp', function (Blueprint $table) {
            $table->id();
            $table->string('lsp_name')->nullable();
            $table->string('license_no')->nullable();
            $table->string('sk_no')->nullable();
            $table->integer('province')->nullable();
            $table->integer('city')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('lsp_file')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('date_lsp_start')->nullable();
            $table->timestamp('date_lsp_end')->nullable();
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
        Schema::dropIfExists('lsp');
    }
}
