<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRonaAwal extends Model
{
    use HasFactory;

    protected $table = 'project_rona_awals';

    protected $fillable = [
        'id_project',
        'id_rona_awal',
        'description',
        'measurement',
    ];

    public function rona_awal()
    {
        return $this->belongsTo(RonaAwal ::class, 'id_rona_awal', 'id');
    }
}
