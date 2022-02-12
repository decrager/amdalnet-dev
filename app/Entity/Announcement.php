<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use App\Entity\Feedback;

class Announcement extends Model
{
    protected $fillable = [
        'pic_name',
        'pic_address',
        'cs_name',
        'cs_address',
        'project_type',
        'project_location',
        'project_scale',
        'proof',
        'potential_impact',
        'start_date',
        'end_date',
        'project_id',
        'project_result',
        'id_applicant',
    ];

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function initiator()
    {
        return $this->belongsTo(Initiator::class, 'id_applicant', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($announcement) {
            $project = Project::find($announcement->project_id);
            $project->workflow_apply('draft-announcement');
            $project->workflow_apply('announce');
            $project->workflow_apply('complete-announcement');
            $project->save();
        });
    }
}
