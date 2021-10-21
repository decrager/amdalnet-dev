<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_consultations', function (Blueprint $table) {
            $table->id();
            $table->integer('announcement_id');
            $table->timestamp('event_date');
            $table->integer('participant');
            $table->string('location');
            $table->text('address');
            $table->text('positive_feedback_summary');
            $table->text('negative_feedback_summary');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_consultations');
    }
}
