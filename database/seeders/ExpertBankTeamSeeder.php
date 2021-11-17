<?php

namespace Database\Seeders;

use App\Entity\ExpertBankTeam;
use App\Entity\ExpertBankTeamMember;
use Illuminate\Database\Seeder;

class ExpertBankTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // manual seeding, maybe one day will changed
        $tuk = new ExpertBankTeam();
        $tuk->name = 'Tim TUK 1';
        $tuk->save();

        // manual seeding, maybe one day will changed
        $member = new ExpertBankTeamMember();
        $member->id_expert_bank_team = 1;
        $member->id_expert_bank = 1;
        $member->position = 'Ketua';
        $member->save();

        $member = new ExpertBankTeamMember();
        $member->id_expert_bank_team = 1;
        $member->id_expert_bank = 2;
        $member->position = 'Sekretaris';
        $member->save();

        $member = new ExpertBankTeamMember();
        $member->id_expert_bank_team = 1;
        $member->id_expert_bank = 3;
        $member->position = 'Anggota';
        $member->save();
    }
}
