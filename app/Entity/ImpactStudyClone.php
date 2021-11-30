<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactStudyClone extends Model
{
    use HasFactory;

    protected $table = 'impact_study_clones';

    protected $fillable = [
        'id_impact_identification_clone',
        'forecast_method',
        'required_information',
        'data_gathering_method',
        'analysis_method',
        'evaluation_method',
    ];
}
