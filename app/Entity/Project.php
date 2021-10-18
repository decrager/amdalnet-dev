<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
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
    ];
}
