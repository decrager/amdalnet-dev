<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvMonitorPlan extends Model
{
    use HasFactory;

    protected $table = 'env_monitor_plans';

    public function impactIdentification()
    {
        return $this->belongsTo(ImpactIdentification::class, 'id_impact_identifications', 'id');
    }

    public function executor()
    {
        return $this->belongsTo(Institution::class, 'executor_institution_id', 'id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Institution::class, 'supervisor_institution_id', 'id');
    }

    public function recipient()
    {
        return $this->belongsTo(Institution::class, 'recipient_institution_id', 'id');
    }
}
