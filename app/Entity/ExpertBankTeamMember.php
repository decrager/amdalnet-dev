<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertBankTeamMember extends Model
{
    use HasFactory;

    protected $table = 'expert_bank_team_members';

    public function expertBank()
    {
        return $this->belongsTo(ExpertBank::class, 'id_expert_bank', 'id');
    }

    public function team()
    {
        return $this->belongsTo(ExpertBankTeam::class, 'id_expert_bank_team', 'id');
    }
}
