<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestingMeetingInvitation extends Model
{
    use HasFactory;

    protected $table = 'testing_meeting_invitations';

    public function meeting()
    {
        return $this->belongsTo(TestingMeeting::class, 'id_testing_meeting', 'id');
    }

    public function expertBankTeamMember()
    {
        return $this->belongsTo(ExpertBankTeamMember::class, 'id_expert_bank_team_member', 'id');
    }

    public function feasibilityTestTeamMember()
    {
        return $this->belongsTo(FeasibilityTestTeamMember::class, 'id_feasibility_test_team_member', 'id');
    }
}
