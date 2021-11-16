<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHypotheticOnImpactIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identifications', function (Blueprint $table) {
            $table->text('potential_impact_evaluation')->nullable();
            $table->boolean('is_hypothetical_significant')->nullable();
            $table->text('initial_study_plan')->nullable();
            $table->string('study_location')->nullable();
            $table->integer('study_length_year')->nullable();
            $table->integer('study_length_month')->nullable();
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
            $table->dropColumn('potential_impact_evaluation');
            $table->dropColumn('is_hypothetical_significant');
            $table->dropColumn('initial_study_plan');
            $table->dropColumn('study_location');
            $table->dropColumn('study_length_year');
            $table->dropColumn('study_length_month');
        });
    }
}
