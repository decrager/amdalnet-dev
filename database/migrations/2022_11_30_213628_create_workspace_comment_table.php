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
            $table->integer('id_user')->nullable();
            $table->integer('page')->nullable();
            $table->text('description')->nullable();
            $table->integer('repair_page')->nullable();
            $table->string('document_type')->nullable();
            $table->integer('reply_to')->nullable();
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
