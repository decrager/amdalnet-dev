<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingReportInvitation extends Model
{
    use HasFactory;

    protected $table = 'meeting_report_invitations';

    public function meeting()
    {
        return $this->belongsTo(MeetingReport::class, 'id_meeting_report', 'id');
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
