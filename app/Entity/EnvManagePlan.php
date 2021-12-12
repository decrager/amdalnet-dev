<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvManagePlan extends Model
{
    use HasFactory;

    protected $table = 'env_manage_plans';

    public function impactIdentification()
    {
        return $this->belongsTo(ImpactIdentification::class, 'id_impact_identifications', 'id');
    }
}
