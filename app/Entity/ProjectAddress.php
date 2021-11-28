<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAddress extends Model
{
    use HasFactory;

    protected $table = 'project_address';

    protected $fillable = [
        'id_project',
        'prov',
        'district',
        'address',
    ];
        
}
