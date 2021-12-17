<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypeInImpactAnalysisDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_analysis_details', function (Blueprint $table) {
            DB::statement('ALTER TABLE impact_analysis_details ALTER COLUMN description TYPE text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('impact_analysis_details', function (Blueprint $table) {
            DB::statement('ALTER TABLE impact_analysis_details ALTER COLUMN description TYPE varchar');
        });
    }
}
