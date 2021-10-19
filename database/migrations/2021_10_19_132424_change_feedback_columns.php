<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeFeedbackColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->text('concern')->nullable();
            $table->text('expectation')->nullable();
            $table->integer('rating')->nullable();
        });
        DB::statement('ALTER TABLE feedbacks RENAME "description" TO "suggestion"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn('concern');
            $table->dropColumn('expectation');
            $table->dropColumn('rating');
        });
        DB::statement('ALTER TABLE feedbacks RENAME "suggestion" TO "description"');
    }
}
