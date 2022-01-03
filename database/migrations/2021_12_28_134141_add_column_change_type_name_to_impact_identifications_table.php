<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnChangeTypeNameToImpactIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identifications', function (Blueprint $table) {
            $table->string('change_type_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('impact_identifications', function (Blueprint $table) {
            $table->dropColumn('change_type_name');
        });
    }
}
