<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class FormulatorTeam extends Model
{
    public function formulator()
    {
        return $this->belongsToMany(Formulator::class, 'teams', 'id_formulator_team', 'id_formulator');
    }
}
