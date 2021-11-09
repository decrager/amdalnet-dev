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

    public function project(){
        return $this->hasOne(Project::class, 'id', 'id_project');
    }

    public function component(){
        return $this->hasOne(Component::class, 'id', 'id_component');
    }

    public function rona_awal(){
        return $this->hasOne(RonaAwal::class, 'id', 'id_rona_awal');
    }
}
