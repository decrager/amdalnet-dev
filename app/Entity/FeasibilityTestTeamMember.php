<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeasibilityTestTeamMember extends Model
{
    use HasFactory;

    protected $table = 'feasibility_test_team_members';

    public function lukMember()
    {
        return $this->belongsTo(LukMember::class, 'id_luk_member', 'id');
    }

    public function expertBank()
    {
        return $this->belongsTo(ExpertBank::class, 'id_expert_bank', 'id');
    }

    public function feasibilityTestTeam()
    {
        return $this->belongsTo(FeasibilityTestTeam::class, 'id_feasibility_test_team', 'id');
    }

    public function tukProject()
    {
        return $this->hasMany(TukProject::class, 'id_feasibility_test_team_member', 'id');
    }

    public function feasibilityTest()
    {
        return $this->hasMany(FeasibilityTest::class, 'id_feasibility_test_team_member', 'id');
    }
}
