<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Param extends Model
{
    use SoftDeletes;

    protected $table = 'params';

    protected $fillable = [
        'name',
    ];
}
