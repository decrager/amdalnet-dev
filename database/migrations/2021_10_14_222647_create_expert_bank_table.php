<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_bank', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_phone_no')->nullable();
            $table->string('expertise')->nullable();
            $table->string('institution')->nullable();
            $table->string('cv_file')->nullable();
            $table->string('cert_luk_file')->nullable();
            $table->string('cert_non_luk_file')->nullable();
            $table->string('ijazah_file')->nullable();
            $table->boolean('status')->nullable();

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
        Schema::dropIfExists('expert_bank');
    }
}
