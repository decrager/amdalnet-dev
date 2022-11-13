<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixShowLandingTuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement($this->dropView());
    }

    private function createView(): string
    {
        return <<<SQL
            create view FixShowLandingTuk as
            select count(tbl.id_luk_member), tbl.authority from (
            select lm.name, lm.id as id_luk_member,
            case 
            when ftt.authority = 'Provinsi' then concat('PROVINSI ',p.name)
            when ftt.authority = 'Kabupaten/Kota' then concat('PROVINSI ',p.name,', ',d.name)
            else UPPER(ftt.authority)
            end authority
            from luk_members lm 
            left join feasibility_test_team_members fttm on fttm.id_luk_member = lm.id
            left join feasibility_test_teams ftt on ftt.id = fttm.id_feasibility_test_team 
            join provinces p on p.id = lm.id_province  
            join districts d on d.id = lm.id_district 
            where ftt.authority is not null
            ) as tbl
            group by tbl.authority
            order by tbl.authority asc
            SQL;
    }
   
    private function dropView(): string
    {
        return <<<SQL
            drop view if exists `FixShowLandingTuk`;
            SQL;
    }
}
