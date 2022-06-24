<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FormulatorTeam extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->hasMany(FormulatorTeamMember::class, 'id_formulator_team', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($formulatorTeam) {
            $project = Project::find($formulatorTeam->id_project);
            if ($project){
                switch ($project->marking){
                    case 'screening-completed':
                        $project->workflow_apply('assign-formulator');
                        $project->save();
                        break;
                    case 'announcement-drafting':
                    case 'announcement':
                    case 'announcement-completed':
                        $project->applyWorkFlowTransition('assign-formulator', 'screening-completed', 'formulator-assignment', false);
                        break;

                }
            }
        });
    }

    public function getEvidenceLetterAttribute()
    {
        if($this->attributes['evidence_letter']) {
            if(str_contains($this->attributes['evidence_letter'], 'storage/')) {
                return $this->attributes['evidence_letter'];
            } else {
                return Storage::url($this->attributes['evidence_letter']);
            }
        }

        return null;
    }
}
