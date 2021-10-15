<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLpjpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lpjp', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('pic')->nullable();
            $table->string('reg_no')->nullable();
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->string('address')->nullable();
            $table->integer('id_district')->nullable();
            $table->integer('id_prov')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('mobile_phone_no')->nullable();
            $table->string('email')->nullable();
            $table->string('url_address')->nullable();
            $table->string('cert_file')->nullable();

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
        Schema::dropIfExists('lpjp');
    }
}
