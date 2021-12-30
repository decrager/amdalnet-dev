<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldInFeasibilityTestTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feasibility_test_team_members', function (Blueprint $table) {
            $table->dropForeign(['id_province']);
            $table->dropForeign(['id_district']);

            $table->dropColumn('role');
            $table->dropColumn('nik');
            $table->dropColumn('nip');
            $table->dropColumn('name');
            $table->dropColumn('institution');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('decision_number');
            $table->dropColumn('decision_file');
            $table->dropColumn('sex');
            $table->dropColumn('cv');
            $table->dropColumn('id_province');
            $table->dropColumn('id_district');
            $table->dropColumn('address');

            $table->integer('id_expert_bank')->nullable();
            $table->integer('id_luk_member')->nullable();

            $table->foreign('id_expert_bank')->references('id')->on('expert_bank')->onDelete('cascade');
            $table->foreign('id_luk_member')->references('id')->on('luk_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feasibility_test_team_members', function (Blueprint $table) {
            $table->dropForeign(['id_expert_bank']);
            $table->dropForeign(['id_luk_member']);

            $table->dropColumn('id_expert_bank');
            $table->dropColumn('id_luk_member');

            $table->string('role')->nullable();
            $table->string('nik')->nullable();
            $table->string('nip')->nullable();
            $table->string('name')->nullable();
            $table->string('institution')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('decision_number')->nullable();
            $table->string('decision_file')->nullable();
            $table->string('sex')->nullable();
            $table->string('cv')->nullable();
            $table->integer('id_province')->nullable();
            $table->integer('id_district')->nullable();
            $table->text('address')->nullable();

            $table->foreign('id_province')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('id_district')->references('id')->on('districts')->onDelete('cascade');
        });
    }
}
