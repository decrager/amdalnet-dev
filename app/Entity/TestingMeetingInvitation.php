<?php

namespace App\Entity;

use App\Laravue\Models\User;
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

    public function governmentInstitution()
    {
        return $this->belongsTo(GovernmentInstitution::class, 'id_government_institution', 'id');
    }

    public function tukSecretaryMember()
    {
        return $this->belongsTo(TukSecretaryMember::class, 'id_tuk_secretary_member', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
