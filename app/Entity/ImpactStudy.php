<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactStudy extends Model
{
    use HasFactory;
    protected $table = 'impact_studies';

    protected $fillable = [
        'id_impact_identification',
        'forecast_method',
        'required_information',
        'data_gathering_method',
        'analysis_method',
        'evaluation_method',
    ];
}
