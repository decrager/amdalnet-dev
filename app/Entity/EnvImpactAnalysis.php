<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvImpactAnalysis extends Model
{
    use HasFactory;

    protected $table = 'env_impact_analysis';

    public function impactIdentification()
    {
        return $this->belongsTo(ImpactIdentificationClone::class, 'id_impact_identifications', 'id');
    }

    public function detail()
    {
        return $this->hasMany(ImpactAnalysisDetail::class, 'id_env_impact_analysis', 'id');
    }

    public function parent()
    {
        return $this->hasMany(SignificantImpactFlowchart::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(SignificantImpactFlowchart::class, 'child_id', 'id');
    }
}
