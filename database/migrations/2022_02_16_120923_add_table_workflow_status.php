<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableWorkflowStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_project')->nullable();
            $table->string('transition')->nullable();
            $table->string('from_place')->nullable();
            $table->string('to_place')->nullable();
            $table->float('duration')->nullable();
            $table->float('duration_total')->nullable();
            $table->json('data')->nullable();
            // $table->timestamp('created_at')->useCurrent();
            $table->timestamps();
            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade');
            $table->index(['id_project', 'from_place', 'created_at']);
            $table->index(['id_project', 'to_place', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workflow_logs');
    }
}
