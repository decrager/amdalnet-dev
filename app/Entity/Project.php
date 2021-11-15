<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ZeroDaHero\LaravelWorkflow\Traits\WorkflowTrait;

class Project extends Model
{
    use SoftDeletes;

    use WorkflowTrait;

    protected $fillable = [
        'project_title',
        'scale',
        'scale_unit',
        'authority',
        'project_type',
        'sector',
        'description',
        'id_applicant',
        'id_prov',
        'id_district',
        'address',
        'field',
        'location_desc',
        'risk_level',
        'project_year',
        'map',
        'map_scale',
        'map_scale_unit',
        'id_formulator_team',
        'announcement_letter',
        'kbli',
        'result_risk',
        'required_doc',
        'biz_type',
        'id_project',
        'type_formulator_team',
        'ktr',
        'id_lpjp',
    ];

    public function team()
    {
        return $this->hasOne(FormulatorTeam::class, 'id_project', 'id');
    }

    public function impactIdentifications()
    {
        return $this->hasMany(ImpactIdentification::class, 'id_project', 'id');
    }

    public function testingMeeting()
    {
        return $this->hasOne(TestingMeeting::class, 'id_project', 'id');
    }
    
    public function province(){
        return $this->hasOne(Province::class, 'id', 'id_prov');
    }
}
