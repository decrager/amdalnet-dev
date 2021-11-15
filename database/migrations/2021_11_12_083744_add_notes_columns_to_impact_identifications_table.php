<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotesColumnsToImpactIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identifications', function (Blueprint $table) {
            $table->text('management_notes')->nullable();
            $table->text('monitoring_notes')->nullable();
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
            $table->dropColumn('management_notes');
            $table->dropColumn('monitoring_notes');
        });
    }
}
