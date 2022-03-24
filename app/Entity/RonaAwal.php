<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RonaAwal extends Model
{
    use SoftDeletes;

    protected $table = 'rona_awal';

    protected $fillable = [
        'id_component_type',
        'name',
    ];
}
