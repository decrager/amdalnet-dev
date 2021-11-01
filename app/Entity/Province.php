<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

   
    public function lpjp()
    {
        return $this->hasMany(Lpjp::class, 'id_prov', 'id');
    }
}
