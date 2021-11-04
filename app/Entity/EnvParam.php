<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class EnvParam extends Model
{
    public $timestamps = false;
    
    protected $table = 'env_params';

    protected $fillable = [
        'kbli_id',
        'id_param',
        'condition',
        'id_unit',
        'doc_req',
        'amdal_type',
        'risk_level',
        'is_param_multisector',
    ];
}
