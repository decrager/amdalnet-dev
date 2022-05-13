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
        'description',
        'measurement',
        'is_andal',
    ];

    public function component()
    {
        return $this->belongsTo(Component::class, 'id_component', 'id');
    }
}
