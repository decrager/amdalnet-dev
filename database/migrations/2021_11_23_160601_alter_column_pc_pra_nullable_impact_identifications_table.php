<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterColumnPcPraNullableImpactIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identifications', function (Blueprint $table) {
            DB::statement('ALTER TABLE impact_identifications ALTER COLUMN id_project_component DROP NOT NULL');
            DB::statement('ALTER TABLE impact_identifications ALTER COLUMN id_project_rona_awal DROP NOT NULL');
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
            DB::statement('ALTER TABLE impact_identifications ALTER COLUMN id_project_component SET NOT NULL');
            DB::statement('ALTER TABLE impact_identifications ALTER COLUMN id_project_rona_awal SET NOT NULL');
        });
    }
}
