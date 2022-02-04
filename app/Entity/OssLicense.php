<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OssLicense extends Model
{
    use HasFactory;

    protected $fillable = ['id_initiator', 'email', 'kd_izin', 'id_izin'];

    public function initiator()
    {
        $this->belongsTo(Initiator::class, 'id_initiator');
    }
}
