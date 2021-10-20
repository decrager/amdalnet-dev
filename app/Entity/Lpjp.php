<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lpjp extends Model
{
    use SoftDeletes;
    
    protected $table = 'lpjp';

    protected $fillable = [
        'name',
        'pic',
        'reg_no',
        'address',
        'id_prov',
        'id_district',
        'mobile_phone_no',
        'email',
        'cert_file',
        'contact_person',
        'phone_no',
        'url_address',
        'date_start',
        'date_end',
    ];
}
