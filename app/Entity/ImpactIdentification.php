<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class ImpactIdentification extends Model
{
    protected $table = 'impact_identifications';
    protected $fillable = [
        'id_project',
        'id_project_component',
        'id_project_rona_awal',
        'id_change_type',
        'id_unit',
        'nominal',
        'potential_impact_evaluation',
        'is_hypothetical_significant',
        'initial_study_plan',
        'study_location',
        'study_length_year',
        'study_length_month',
    ];

    public function project(){
        return $this->belongsTo(Project::class, 'id_project');
    }

    public function component(){
        return $this->belongsTo(ProjectComponent::class, 'id_project_component');
    }

    public function ronaAwal(){
        return $this->belongsTo(ProjectRonaAwal::class, 'id_project_rona_awal');
    }

    public function changeType(){
        return $this->belongsTo(ChangeType::class, 'id_change_type');
    }

    public function unit(){
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function envImpactAnalysis()
    {
        return $this->hasOne(EnvImpactAnalysis::class, 'id_impact_identifications', 'id');
    }
    
    public function envManagePlan()
    {
        return $this->hasOne(EnvManagePlan::class, 'id_impact_identifications', 'id');
    }

    public function envMonitorPlan()
    {
        return $this->hasOne(EnvMonitorPlan::class, 'id_impact_identifications', 'id');
    }

    public function impactStudy() {
        return $this->hasOne(ImpactStudy::class, 'id_impact_identification', 'id');
    }
}
