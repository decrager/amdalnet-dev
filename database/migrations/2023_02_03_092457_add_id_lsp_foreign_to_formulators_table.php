<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdLspForeignToFormulatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE formulators ALTER COLUMN id_lsp TYPE integer USING (trim(id_lsp))::integer');

        Schema::table('formulators', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lsp')->change(true);
            $table->foreign('id_lsp')
                ->references('id')
                ->on('lsp')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulators', function (Blueprint $table) {
            $table->dropForeign('formulators_id_lsp_foreign');
        });
    }
}
