<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use ZeroDaHero\LaravelWorkflow\Traits\WorkflowTrait;

class BlogPost extends Model
{
    //
    use WorkflowTrait;
}
