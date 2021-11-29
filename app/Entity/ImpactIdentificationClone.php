<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactIdentificationClone extends Model
{
    use HasFactory;

    protected $table = 'impact_identification_clones';

    protected $fillable = [
        'id_impact_identification',
        'id_project',
        'id_change_type',
        'id_unit',
        'nominal',
        'potential_impact_evaluation',
        'is_hypothetical_significant',
        'initial_study_plan',
        'study_location',
        'study_length_year',
        'study_length_month',
        'id_sub_project_component',
        'id_sub_project_rona_awal',
    ];

    public function project(){
        return $this->belongsTo(Project::class, 'id_project');
    }

    public function subProjectComponent(){
        return $this->belongsTo(SubProjectComponent::class, 'id_sub_project_component');
    }

    public function subProjectRonaAwal(){
        return $this->belongsTo(SubProjectRonaAwal::class, 'id_sub_project_rona_awal');
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
        return $this->hasOne(ImpactStudyClone::class, 'id_impact_identification_clone', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_impact_identification', 'id');
    }

    public function potentialImpactEvaluation()
    {
        return $this->hasMany(PotentialImpactEvaluation::class, 'id_impact_identification', 'id');
    }

    public function real()
    {
        return $this->belongsTo(ImpactIdentification::class, 'id_impact_identification', 'id');
    }
}
