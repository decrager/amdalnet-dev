<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessEnvParamsTemp extends Model
{
    use HasFactory;
    protected $table = 'business_env_params_temp';
    public $timestamps = false;
}
