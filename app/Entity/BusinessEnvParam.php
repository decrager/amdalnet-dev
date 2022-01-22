<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessEnvParam extends Model
{
    use HasFactory;
    protected $fillable = [
        'business_id',
        'id_param',
        'condition',
        'id_unit',
        'doc_req',
        'amdal_type',
        'risk_level',
        'is_param_multisector',
    ];

    public $timestamps = false;

    public function business(){
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function param(){
        return $this->hasOne(Param::class, 'id', 'id_param');
    }

    public function unit(){
        return $this->hasOne(Unit::class, 'id', 'id_unit');
    }
}
