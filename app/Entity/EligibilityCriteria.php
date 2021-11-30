<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EligibilityCriteria extends Model
{
    use HasFactory;

    protected $table = 'eligibility_criterieas';

    public function feasibility()
    {
        return $this->hasMany(FeasibilityTestDetail::class, 'id_eligibility_criteria', 'id');
    }
}
