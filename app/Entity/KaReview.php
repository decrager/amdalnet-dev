<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaReview extends Model
{
    use HasFactory;

    protected $table = 'ka_reviews';

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }
}
