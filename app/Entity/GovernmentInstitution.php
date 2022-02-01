<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentInstitution extends Model
{
    use HasFactory;

    protected $table = 'government_institutions'; 

    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id_district', 'id');
    }
}
