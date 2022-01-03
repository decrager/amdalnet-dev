<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolisticEvaluation extends Model
{
    use HasFactory;

    protected $table = 'holistic_evaluations';

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }
}
