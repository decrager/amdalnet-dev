<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppParam extends Model
{
    use SoftDeletes;

    protected $table = 'app_params';

    protected $fillable = [
        'parameter_name',
        'title',
        'value',
        'is_numeric'
    ];
}
