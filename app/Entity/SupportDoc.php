<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class SupportDoc extends Model
{
    protected $fillable = ['id_project','name','file'];
}
