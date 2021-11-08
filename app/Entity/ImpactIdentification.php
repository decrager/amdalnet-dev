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
        'id_change_type',
        'id_unit',
        'nominal',
    ];

    public function project(){
        return $this->hasOne(Project::class, 'id', 'id_project');
    }

    public function component(){
        return $this->hasOne(Component::class, 'id', 'id_component');
    }

    public function ronaAwal(){
        return $this->hasOne(RonaAwal::class, 'id', 'id_rona_awal');
    }

    public function changeType(){
        return $this->hasOne(ChangeType::class, 'id', 'id_change_type');
    }

    public function unit(){
        return $this->hasOne(Unit::class, 'id', 'id_unit');
    }
}
