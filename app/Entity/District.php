<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    
    public function lpjp()
    {
        return $this->hasMany(Lpjp::class, 'id_district', 'id');
    }
}
