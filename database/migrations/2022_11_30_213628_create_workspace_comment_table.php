<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkspaceCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workspace_comment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_project')->references('id')->on('projects');
            $table->integer('id_user')->nullable();
            $table->string('page');
            $table->text('suggest');
            $table->string('page_fix')->nullable();
            $table->text('response')->nullable();
            $table->string('document_type')->nullable();
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
        Schema::dropIfExists('workspace_comment');
    }
}