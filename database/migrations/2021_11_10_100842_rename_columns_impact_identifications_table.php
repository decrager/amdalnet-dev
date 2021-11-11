<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameColumnsImpactIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impact_identifications', function (Blueprint $table) {
            DB::statement('ALTER TABLE impact_identifications RENAME "id_component" TO "id_project_component"');
            DB::statement('ALTER TABLE impact_identifications RENAME "id_rona_awal" TO "id_project_rona_awal"');
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
            DB::statement('ALTER TABLE impact_identifications RENAME "id_project_component" TO "id_component"');
            DB::statement('ALTER TABLE impact_identifications RENAME "id_project_rona_awal" TO "id_rona_awal"');
        });
    }
}
