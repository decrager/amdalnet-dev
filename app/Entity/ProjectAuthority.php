<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAuthority extends Model
{
    use HasFactory;

    protected $table = 'project_authorities';

    protected $guarded = [];
}
