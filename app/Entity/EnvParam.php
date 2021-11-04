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

    public function param(){
        return $this->hasOne(Param::class, 'id', 'id_param');
    }

    public function unit(){
        return $this->hasOne(Unit::class, 'id', 'id_unit');
    }
}
