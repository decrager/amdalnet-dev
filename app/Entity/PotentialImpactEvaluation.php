<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotentialImpactEvaluation extends Model
{
    use HasFactory;
    protected $fillabel = [
        'id_impact_identification',
        'id_pie_param',
        'text'
    ];

    public function pieParam()
    {
        return $this->belongsTo(MasterPotentialImpactEvaluationParam::class, 'id_pie_param', 'id');
    }
}
