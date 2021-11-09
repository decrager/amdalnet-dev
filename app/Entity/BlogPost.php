<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use ZeroDaHero\LaravelWorkflow\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\BlogPostFactory;

class BlogPost extends Model
{
    //
    use WorkflowTrait;
    use HasFactory;

    protected static function newFactory()
    {
        return BlogPostFactory::new();
    }
}
