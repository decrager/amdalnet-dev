<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeasibilityTestRecap extends Model
{
    use HasFactory;

    protected $table = 'feasibility_test_recaps';

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }
}
