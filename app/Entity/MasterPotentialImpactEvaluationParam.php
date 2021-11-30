<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPotentialImpactEvaluationParam extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];
}
