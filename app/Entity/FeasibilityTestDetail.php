<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeasibilityTestDetail extends Model
{
    use HasFactory;

    protected $table = 'feasibility_test_details';

    public function eligibility()
    {
        return $this->belongsTo(EligibilityCriteria::class, 'id_eligibility_criteria', 'id');
    }
}
