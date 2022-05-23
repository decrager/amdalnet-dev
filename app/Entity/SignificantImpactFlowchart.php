<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignificantImpactFlowchart extends Model
{
    use HasFactory;

    protected $table = 'significant_impact_flowcharts';

    public function parent()
    {
        return $this->belongsTo(EnvImpactAnalysis::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->belongsTo(EnvImpactAnalysis::class, 'child_id', 'id');
    }
}
