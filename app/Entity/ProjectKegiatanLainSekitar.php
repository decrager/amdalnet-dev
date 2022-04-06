<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectKegiatanLainSekitar extends Model
{
    use HasFactory;

    protected $table = 'project_kegiatan_lain_sekitars';
    protected $fillable = [
        'project_id',
        'kegiatan_lain_sekitar_id',
        'description',
        'measurement',
        'address',
        'province_id',
        'district_id',
    ];
}
