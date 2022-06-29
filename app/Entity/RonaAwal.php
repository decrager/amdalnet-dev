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
        'is_master',
        'originator_id'
    ];

    public function projectRonaAwal()
    {
        return $this->hasMany(ProjectRonaAwal::class, 'id_rona_awal', 'id');
    }

    public function componentType()
    {
        return $this->belongsTo(ComponentType::class, 'id_component_type', 'id');
    }
}
