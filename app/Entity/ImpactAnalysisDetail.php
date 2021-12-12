<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactAnalysisDetail extends Model
{
    use HasFactory;

    protected $table = 'impact_analysis_details';

    public function importantTrait()
    {
        return $this->belongsTo(ImportantTrait::class, 'id_important_trait', 'id');
    }

    public function impactAnalysis()
    {
        return $this->belongsTo(EnvImpactAnalysis::class, 'id_env_impact_analysis', 'id');
    }
}
