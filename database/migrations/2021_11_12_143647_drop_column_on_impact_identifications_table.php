<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnOnImpactIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('impact_identifications', function (Blueprint $table) {
            $table->dropColumn('management_effort');
            $table->dropColumn('management_location');
            $table->dropColumn('management_period');
            $table->dropColumn('management_institution_executor');
            $table->dropColumn('management_institution_recipient');
            $table->dropColumn('management_institution_supervisor');
            $table->dropColumn('monitoring_effort');
            $table->dropColumn('monitoring_location');
            $table->dropColumn('monitoring_period');
            $table->dropColumn('monitoring_institution_executor');
            $table->dropColumn('monitoring_institution_recipient');
            $table->dropColumn('monitoring_institution_supervisor');
            $table->dropColumn('management_notes');
            $table->dropColumn('monitoring_notes');
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
            $table->text('management_effort')->nullable();
            $table->string('management_location')->nullable();
            $table->string('management_period')->nullable();
            $table->integer('management_institution_executor')->nullable();
            $table->integer('management_institution_recipient')->nullable();
            $table->integer('management_institution_supervisor')->nullable();
            $table->text('monitoring_effort')->nullable();
            $table->string('monitoring_location')->nullable();
            $table->string('monitoring_period')->nullable();
            $table->integer('monitoring_institution_executor')->nullable();
            $table->integer('monitoring_institution_recipient')->nullable();
            $table->integer('monitoring_institution_supervisor')->nullable();
            $table->text('management_notes')->nullable();
            $table->text('monitoring_notes')->nullable();
        });
    }
}
