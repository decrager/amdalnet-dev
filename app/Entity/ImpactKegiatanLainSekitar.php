<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactKegiatanLainSekitar extends Model
{
    use HasFactory;

    protected $table = 'impact_kegiatan_lain_sekitars';
    protected $fillable = [
        'id_impact_identification',
        'id_project_kegiatan_lain_sekitar',
        'is_andal'
    ];
}
