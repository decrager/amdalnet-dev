<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestingMeeting extends Model
{
    use HasFactory;

    protected $table = 'testing_meetings';

    public function invitations()
    {
        return $this->hasMany(TestingMeetingInvitation::class, 'id_testing_meeting', 'id');
    }
}
