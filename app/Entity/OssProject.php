<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class OssProject extends Model
{
    protected $casts = [
        'json_content' => 'array'
    ];
}
