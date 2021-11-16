<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingReport extends Model
{
    use HasFactory;

    protected $table = 'meeting_reports';

    public function invitations()
    {
        return $this->hasMany(MeetingReportInvitation::class, 'id_meeting_report', 'id');
    }

    public function initiator()
    {
        return $this->belongsTo(Initiator::class, 'id_initiator', 'id');
    }
}
