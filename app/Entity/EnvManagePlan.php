<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvManagePlan extends Model
{
    use HasFactory;

    protected $table = 'env_manage_plans';

    protected $fillable = [
        'id_impact_identifications',
        'form',
        'location',
        'period',
        'executor',
        'supervisor',
        'report_recipient',
        'description',
    ];

    public function impactIdentification()
    {
        return $this->belongsTo(ImpactIdentificationClone::class, 'id_impact_identifications', 'id');
    }
}
