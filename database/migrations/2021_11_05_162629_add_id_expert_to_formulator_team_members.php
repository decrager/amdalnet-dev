<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdExpertToFormulatorTeamMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulator_team_members', function (Blueprint $table) {
            $table->integer('id_expert')->nullable()->after('id_formulator');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulator_team_members', function (Blueprint $table) {
            $table->dropColumn('id_expert');
        });
    }
}
