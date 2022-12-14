<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeKdIzinToOssLicensesTable extends Migration
{
    public function up()
    {
        Schema::table('oss_licenses', function (Blueprint $table) {
            $table->string('kd_izin')->nullable(true)->change();
            $table->string('id_izin')->nullable(true)->change();
        });
    }

    public function down()
    {
        Schema::table('oss_licenses', function (Blueprint $table) {
            $table->string('kd_izin')->nullable(false)->change();
            $table->string('id_izin')->nullable(false)->change();
        });
    }
}
