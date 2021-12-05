<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterColumnDescriptionOnSubProjectRonaAwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_project_rona_awals', function (Blueprint $table) {
            DB::statement('ALTER TABLE sub_project_rona_awals ALTER COLUMN description_common TYPE text');
            DB::statement('ALTER TABLE sub_project_rona_awals ALTER COLUMN description_specific TYPE text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_project_rona_awals', function (Blueprint $table) {
            DB::statement('ALTER TABLE sub_project_rona_awals ALTER COLUMN description_common TYPE varchar');
            DB::statement('ALTER TABLE sub_project_rona_awals ALTER COLUMN description_specific TYPE varchar');
        });
    }
}
