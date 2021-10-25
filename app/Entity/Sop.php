<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sop extends Model
{
    use SoftDeletes;
    
    protected $table = 'sops';

    protected $fillable = [
        'id_component',
        'id_rona_awal',
        'mgmt_form',
        'mgmt_period',
        'monitoring_form',
        'monitoring_time',
        'monitoring_freq',
        'monitoring_date_field',
        'name',
        'impact',
        'other_impact',
        'monitoring_period',
        'impact_quantity',
        'code',
        'effective_date',
    ];
}
