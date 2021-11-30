<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterTableProjectMapAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            DB::statement('ALTER TABLE project_map_attachments ADD CONSTRAINT project_map_attachment_check CHECK (attachment_type::TEXT = ANY (ARRAY[\'tapak\'::CHARACTER VARYING, \'social\'::CHARACTER VARYING, \'ecology\'::CHARACTER VARYING, \'study\'::CHARACTER VARYING]::TEXT[]))');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
