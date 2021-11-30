<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ZeroDaHero\LaravelWorkflow\Traits\WorkflowTrait;

class Project extends Model
{
    use SoftDeletes;

    use WorkflowTrait;

    // protected $fillable = [
    //     'project_title',
    //     'scale',
    //     'scale_unit',
    //     'authority',
    //     'project_type',
    //     'sector',
    //     'description',
    //     'id_applicant',
    //     'id_prov',
    //     'id_district',
    //     'address',
    //     'field',
    //     'location_desc',
    //     'risk_level',
    //     'project_year',
    //     'map',
    //     'map_scale',
    //     'map_scale_unit',
    //     'id_formulator_team',
    //     'announcement_letter',
    //     'kbli',
    //     'result_risk',
    //     'required_doc',
    //     'biz_type',
    //     'id_project',
    //     'type_formulator_team',
    //     'ktr',
    //     'id_lpjp',
    //     'pre_agreement',
    //     'pre_agreement_file',
    // ];

    protected $guarded = [];

    public function team()
    {
        return $this->hasOne(FormulatorTeam::class, 'id_project', 'id');
    }

    public function impactIdentifications()
    {
        return $this->hasMany(ImpactIdentification::class, 'id_project', 'id');
    }
    
    public function impactIdentificationsClone()
    {
        return $this->hasMany(ImpactIdentificationClone::class, 'id_project', 'id');
    }

    public function address()
    {
        return $this->hasMany(ProjectAddress::class, 'id_project', 'id');
    }

    public function listSubProject()
    {
        return $this->hasMany(SubProject::class, 'id_project', 'id');
    }

    public function testingMeeting()
    {
        return $this->hasMany(TestingMeeting::class, 'id_project', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id_district', 'id');
    }

    public function province(){
        return $this->hasOne(Province::class, 'id', 'id_prov');
    }

    public function initiator()
    {
        return $this->belongsTo(Initiator::class, 'id_applicant', 'id');
    }

    public function subProjects()
    {
        return $this->hasMany(SubProject::class, 'id_project', 'id');
    }

    public function mapFiles()
    {
        return $this->hasMany(ProjectMapAttachment::class, 'id_project', 'id');
    }
}
