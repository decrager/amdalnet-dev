<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectMapAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_map_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('id_project')->unsigned();
            $table->foreign('id_project')->references('id')->on('projects');
            $table->enum('attachment_type', ['social','ecology','study']);
            $table->enum('file_type', ['SHP', 'PDF']);
            $table->string('original_filename');
            $table->string('stored_filename');
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
        Schema::dropIfExists('project_map_attachments');
    }
}
