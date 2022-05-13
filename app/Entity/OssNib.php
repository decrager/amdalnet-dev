<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OssNib extends Model
{
    use HasFactory;
    
    protected $casts = [
        'json_content' => 'array'
    ];

    protected $guarded = [];
}
