<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $table = 'policys';

    protected $guarded = [];


    public function regulation()
    {
        return $this->belongsTo(\App\Entity\Regulation::class, 'regulation_type', 'id');
    }
}
