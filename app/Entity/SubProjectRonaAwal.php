<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProjectRonaAwal extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_sub_project',
        'id_rona_awal',
        'name',
        'id_component_type',
        'description',
    ];

    public function subProject()
    {
        return $this->belongsTo(SubProject::class, 'id_sub_project');
    }
    
    public function ronaAwal()
    {
        return $this->belongsTo(RonaAwal::class, 'id_rona_awal');
    }

    public function componentType()
    {
        return $this->belongsTo(ComponentType::class, 'id_component_type');
    }

}
