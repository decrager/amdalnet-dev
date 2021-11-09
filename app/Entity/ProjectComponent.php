<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectComponent extends Model
{
    use HasFactory;

    protected $table = 'project_components';

    protected $fillable = [
        'id_project',
        'id_component',
        'name',
        'id_project_stage'
    ];

    public function component(){
        return $this->hasOne(Component::class, 'id', 'id_component');
    }
}
