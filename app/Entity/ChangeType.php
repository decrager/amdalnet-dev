<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'change_types';

    protected $fillable = [
        'name',
        'is_primary',
    ];
}
