<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectFilter extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_project',
        'wastewater',
        'disposal_wastewater',
        'utilization_wastewater',
        'high_pollution',
        'emission',
        'chimney',
        'genset',
        'high_emission',
        'b3',
        'collect_b3',
        'hoard_b3',
        'process_b3',
        'utilization_b3',
        'dumping_b3',
        'tps',
        'traffic',
        'low_traffic',
        'mid_traffic',
        'high_traffic',
        'nothing',
    ];
}
