<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lpjp extends Model
{
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

    public function province()
    {
        return $this->belongsTo(Province::class, 'id_prov', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id_district', 'id');
    }

    public function members()
    {
        return $this->hasMany(Formulator::class, 'id_lpjp', 'id');
    }


    public function getCertFileAttribute()
    {
        if($this->attributes['cert_file']) {
            if(str_contains($this->attributes['cert_file'], 'storage/')) {
                return $this->attributes['cert_file'];
            } else {
                // return Storage::url($this->attributes['cert_file']);
                return Storage::disk('public')->temporaryUrl($this->attributes['cert_file'], now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')));
            }
        }

        return null;
    }
}
