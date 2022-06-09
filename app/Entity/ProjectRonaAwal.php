<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectRonaAwal extends Model
{
    use HasFactory;

    protected $table = 'project_rona_awals';

    protected $fillable = [
        'id_project',
        'id_rona_awal',
        'description',
        'measurement',
        'is_andal'
    ];

    public function rona_awal()
    {
        return $this->belongsTo(RonaAwal ::class, 'id_rona_awal', 'id');
    }

    public function getFileAttribute()
    {
        if($this->attributes['file']) {
            if(str_contains($this->attributes['file'], 'storage/')) {
                return $this->attributes['file'];
            } else {
                return Storage::url($this->attributes['file']);
            }
        }

        return null;
    }
}
