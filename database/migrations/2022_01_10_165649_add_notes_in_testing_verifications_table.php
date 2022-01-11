<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotesInTestingVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testing_verifications', function (Blueprint $table) {
            $table->longText('notes')->nullable();
            $table->boolean('is_complete')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('testing_verifications', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropColumn('is_complete');
        });
    }
}
