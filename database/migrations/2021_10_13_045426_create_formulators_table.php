<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulators', function (Blueprint $table) {
            $table->id();
            $table->string('id_formulator')->nullable();
            $table->string('name')->nullable();
            $table->string('expertise')->nullable();
            $table->string('cert_no')->nullable();
            $table->string('nip')->nullable();
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->string('cert_file')->nullable();
            $table->string('cv_file')->nullable();
            $table->string('reg_no')->nullable();
            $table->string('id_institution')->nullable();
            $table->string('membership_status')->nullable();
            $table->string('id_lsp')->nullable();
            $table->string('email')->unique();

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
        Schema::dropIfExists('formulators');
    }
}
