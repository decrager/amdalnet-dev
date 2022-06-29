<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MeetingReport extends Model
{
    use HasFactory;

    protected $table = 'meeting_reports';

    public function invitations()
    {
        return $this->hasMany(MeetingReportInvitation::class, 'id_meeting_report', 'id');
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
}
