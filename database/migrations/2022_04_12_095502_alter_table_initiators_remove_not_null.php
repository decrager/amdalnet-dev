<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableInitiatorsRemoveNotNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('initiators', function (Blueprint $table) {
            DB::statement('ALTER TABLE initiators ALTER COLUMN phone DROP NOT NULL');
            DB::statement('ALTER TABLE initiators ALTER COLUMN "address" DROP NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('initiators', function (Blueprint $table) {
            DB::statement('ALTER TABLE initiators ALTER COLUMN phone NOT NULL');
            DB::statement('ALTER TABLE initiators ALTER COLUMN "address" NOT NULL');
        });
    }
}
