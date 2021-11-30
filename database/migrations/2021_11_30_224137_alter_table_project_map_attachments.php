<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProjectMapAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_map_attachments', function (Blueprint $table) {
            $table->enum('attachment_type', ['tapak', 'social', 'ecology', 'study'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_map_attachments', function (Blueprint $table) {
            $table->dropColumn('attachment_type');
        });
    }
}
