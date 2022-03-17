<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TukSecretaryMember extends Model
{
    use HasFactory;

    protected $table = 'tuk_secretary_members';

    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id_district', 'id');
    }

    public function feasibilityTestTeam()
    {
        return $this->belongsTo(FeasibilityTestTeam::class, 'id_feasibility_test_team', 'id');
    }
}
