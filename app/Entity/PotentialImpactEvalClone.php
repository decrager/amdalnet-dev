<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotentialImpactEvalClone extends Model
{
    use HasFactory;
    
    protected $table = 'potential_impact_eval_clones';
    protected $fillabel = [
        'id_impact_identification_clone',
        'id_pie_param',
        'text'
    ];
}
