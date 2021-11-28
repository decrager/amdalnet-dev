<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class FormulatorTeam extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->hasMany(FormulatorTeamMember::class, 'id_formulator_team', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }
}
