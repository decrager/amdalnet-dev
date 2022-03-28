<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeasibilityTest extends Model
{
    use HasFactory;

    public function detail()
    {
        return $this->hasMany(FeasibilityTestDetail::class, 'id_feasibility_test', 'id');
    }

    public function feasibilityTestTeamMember()
    {
        return $this->belongsTo(FeasibilityTestTeamMember::class, 'id_feasibility_test_team_member', 'id');
    }

    public function tukSecretaryMember()
    {
        return $this->belongsTo(TukSecretaryMember::class, 'id_tuk_secretary_member', 'id');
    }
}
