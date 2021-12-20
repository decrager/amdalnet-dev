<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArcgisServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arcgis_service', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_category')->nullable(false);
            $table->string('url_service')->nullable(false);
            $table->string('source')->nullable(false);
            $table->string('organization')->nullable(true);
            $table->tinyInteger('active')->default(0)->nullable(false);
            $table->timestamps();
        });

        Schema::create('arcgis_service_category', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->nullable(false);
            $table->tinyInteger('active')->default(0)->nullable(false);
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
        Schema::dropIfExists('arcgis_service');
        Schema::dropIfExists('arcgis_service_category');
    }
}
