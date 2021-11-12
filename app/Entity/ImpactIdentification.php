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
        'management_effort',
        'management_location',
        'management_period',
        'management_institution_executor',
        'management_institution_recipient',
        'management_institution_supervisor',
        'monitoring_effort',
        'monitoring_location',
        'monitoring_period',
        'monitoring_institution_executor',
        'monitoring_institution_recipient',
        'monitoring_institution_supervisor',
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

    public function managementInstitutionExecutor()
    {
        return $this->belongsTo(Institution::class, 'management_institution_executor');
    }
    
    public function managementInstitutionRecipient()
    {
        return $this->belongsTo(Institution::class, 'management_institution_recipient');
    }

    public function managementInstitutionSupervisor()
    {
        return $this->belongsTo(Institution::class, 'management_institution_supervisor');
    }

    public function monitoringInstitutionExecutor()
    {
        return $this->belongsTo(Institution::class, 'monitoring_institution_executor');
    }
    
    public function monitoringInstitutionRecipient()
    {
        return $this->belongsTo(Institution::class, 'monitoring_institution_recipient');
    }

    public function monitoringInstitutionSupervisor()
    {
        return $this->belongsTo(Institution::class, 'monitoring_institution_supervisor');
    }
}
