<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertBankTeam extends Model
{
    use HasFactory;

    protected $table = 'expert_bank_teams';

    public function member()
    {
        return $this->hasMany(ExpertBankTeamMember::class, 'id_expert_bank_team', 'id');
    }
}
