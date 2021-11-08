<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeType extends Model
{
    use HasFactory;

    protected $table = 'change_types';

    protected $fillable = [
        'name'
    ];
}
