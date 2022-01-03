<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProjectComponent extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_sub_project',
        'id_component',
        'name',
        'id_project_stage',
        'description_common',
        'description_specific',
        'definition',
        'unit',
    ];

    public function subProject()
    {
        return $this->belongsTo(SubProject::class, 'id_sub_project');
    }

    public function component()
    {
        return $this->belongsTo(Component::class, 'id_component');
    }

    public function projectStage()
    {
        return $this->belongsTo(ProjectStage::class, 'id_project_stage');
    }
}
