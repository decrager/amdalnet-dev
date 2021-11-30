<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProject extends Model
{
    use HasFactory;
    protected $fillable = [
        'kbli',
        'name',
        'result',
        'type',
        'biz_type',
        'id_project',
        'scale',
        'scale_unit',
        'sector',
        'biz_name',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project');
    }

    public function components()
    {
        return $this->hasMany(SubProjectComponent::class, 'id_sub_project', 'id');
    }

    public function ronaAwals()
    {
        return $this->hasMany(SubProjectRonaAwal::class, 'id_sub_project', 'id');
    }
}
