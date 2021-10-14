<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use ZeroDaHero\LaravelWorkflow\Traits\WorkflowTrait;

class Project extends Model
{
    //
    use WorkflowTrait;
}
