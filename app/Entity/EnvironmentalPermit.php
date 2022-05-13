<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentalPermit extends Model
{
    use HasFactory;

    protected $table = 'environmental_permits';

    protected $guarded = [];
}
