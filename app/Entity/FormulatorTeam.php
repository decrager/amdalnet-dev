<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class FormulatorTeam extends Model
{

    public function member()
    {
        return $this->hasMany(FormulatorTeamMember::class, 'id_formulator_team', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }
}
