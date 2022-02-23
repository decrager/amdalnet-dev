<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOssNibsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oss_nibs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nib');
            $table->date('nib_submit_date');
            $table->date('nib_published_date');
            $table->date('nib_updated_date');
            $table->string('oss_id');
            $table->string('id_izin');
            $table->string('kd_izin');
            $table->string('company_name');
            $table->string('company_email');
            $table->longText('json_content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oss_nibs');
    }
}
