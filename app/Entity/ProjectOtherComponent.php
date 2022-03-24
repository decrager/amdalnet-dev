<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectOtherComponent extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_project',
        'description',
        'measurement',
    ];
}
