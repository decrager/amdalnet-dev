<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'pic_name',
        'pic_address',
        'cs_name',
        'cs_address',
        'project_type',
        'project_location',
        'project_scale',
        'proof',
        'potential_impact',
        'start_date',
        'end_date',
    ];
}
