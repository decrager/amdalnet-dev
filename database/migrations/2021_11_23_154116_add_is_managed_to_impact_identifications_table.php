<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsManagedToImpactIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identifications', function (Blueprint $table) {
            $table->boolean('is_managed')->nullable()->after('is_hypothetical_significant');
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
            $table->dropColumn('is_managed');
        });
    }
}
