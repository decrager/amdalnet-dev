<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responder_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->integer('announcement_id');
            $table->string('name');
            $table->string('id_card_number');
            $table->string('phone');
            $table->string('email');
            $table->string('photo_filepath')->nullable();
            $table->text('description');
            $table->integer('responder_type_id');
            $table->boolean('is_relevant')->default(0);
            $table->integer('set_relevant_by')->nullable();
            $table->timestamp('set_relevant_at')->nullable();
            $table->foreign('responder_type_id')->references('id')->on('responder_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('responder_types');
    }
}
