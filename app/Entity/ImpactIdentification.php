<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class ImpactIdentification extends Model
{
    protected $table = 'impact_identifications';
    protected $fillable = [
        'id_project',
        'id_component',
        'id_rona_awal',
    ];
}
