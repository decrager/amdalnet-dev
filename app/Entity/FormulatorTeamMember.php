<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulatorTeamMember extends Model
{
    use HasFactory;

    public function formulator()
    {
        return $this->belongsTo(Formulator::class, 'id_formulator', 'id');
    }

    public function expert()
    {
        return $this->belongsTo(EnvironmentalExpert::class, 'id_expert', 'id');
    }

    public function team()
    {
        return $this->belongsTo(FormulatorTeam::class, 'id_formulator_team', 'id');
    }
}
