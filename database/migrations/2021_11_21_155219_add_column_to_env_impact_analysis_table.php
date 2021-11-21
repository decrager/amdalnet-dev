<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToEnvImpactAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('env_impact_analysis', function (Blueprint $table) {
            $table->dropColumn('important_trait');
            $table->string('impact_type')->nullable();
            $table->text('studies_condition')->nullable();
            $table->text('condition_dev_no_plan')->nullable();
            $table->text('condition_dev_with_plan')->nullable();
            $table->text('impact_size_difference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('env_impact_analysis', function (Blueprint $table) {
            $table->string('important_trait')->nullable();
            $table->dropColumn('studies_condition');
            $table->dropColumn('condition_dev_no_plan');
            $table->dropColumn('condition_dev_with_plan');
            $table->dropColumn('impact_size_difference');
        });
    }
}
