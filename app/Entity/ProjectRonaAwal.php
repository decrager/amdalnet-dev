<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRonaAwal extends Model
{
    use HasFactory;

    protected $table = 'project_rona_awals';

    protected $fillable = [
        'id_project',
        'id_rona_awal',
        'name',
        'id_component_type'
    ];

    public function ronaAwal(){
        return $this->hasOne(RonaAwal::class, 'id', 'id_rona_awal');
    }
}
