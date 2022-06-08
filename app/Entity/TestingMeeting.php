<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TestingMeeting extends Model
{
    use HasFactory;

    protected $table = 'testing_meetings';

    public function invitations()
    {
        return $this->hasMany(TestingMeetingInvitation::class, 'id_testing_meeting', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }

    public function getFileAttribute()
    {
        if($this->attributes['file']) {
            if(str_contains($this->attributes['file'], 'storage/')) {
                return $this->attributes['file'];
            } else {
                return Storage::url($this->attributes['file']);
            }
        }

        return null;
    }

    public function getInvitationFileAttribute()
    {
        if($this->attributes['invitation_file']) {
            if(str_contains($this->attributes['invitation_file'], 'storage/')) {
                return $this->attributes['invitation_file'];
            } else {
                return Storage::url($this->attributes['invitation_file']);
            }
        }

        return null;
    }
}
