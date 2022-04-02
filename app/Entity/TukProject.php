<?php

namespace App\Entity;

use App\Laravue\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TukProject extends Model
{
    use HasFactory;

    protected $table = 'tuk_projects';

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }

    public function feasibilityTestTeamMember()
    {
        return $this->belongsTo(FeasibilityTestTeamMember::class, 'id_feasibility_test_team_member', 'id');
    }

    public function tukSecretaryMember()
    {
        return $this->belongsTo(TukSecretaryMember::class, 'id_tuk_secretary_member', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
