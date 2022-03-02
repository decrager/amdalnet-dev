<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvPlanInstitution extends Model
{
    use HasFactory;

    protected $table = 'env_plan_institutions';

    public function impactIdentification()
    {
        return $this->belongsTo(ImpactIdentificationClone::class, 'id_impact_identification', 'id');
    }
}
