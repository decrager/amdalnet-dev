<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColumnToEnvManagePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('env_manage_plans', function (Blueprint $table) {
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN success_indicator TYPE text');
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN form TYPE text');
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN location TYPE text');
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN period TYPE text');
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN institution TYPE text');
            $table->text('impact_source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('env_manage_plans', function (Blueprint $table) {
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN success_indicator TYPE varchar');
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN form TYPE varchar');
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN location TYPE varchar');
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN period TYPE varchar');
            DB::statement('ALTER TABLE env_manage_plans ALTER COLUMN institution TYPE varchar');
            $table->dropColumn('impact_source');
        });
    }
}
