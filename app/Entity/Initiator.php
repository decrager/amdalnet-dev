<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Initiator extends Model
{
    use SoftDeletes;

    // protected $fillable = [
    //     'name',
    //     'pic',
    //     'email',
    //     'phone',
    //     'address',
    //     'user_type',
    //     'nib',
    // ];

    protected $guarded = [];
}
