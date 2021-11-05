<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentalExpert extends Model
{
    use HasFactory;

    public function teamMembers()
    {
        return $this->hasMany(FormulatorTeamMember::class, 'id_expert', 'id');
    }
}
