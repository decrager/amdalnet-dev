<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Formulator extends Model
{
    // protected $fillable = [
    //     'id_formulator',
    //     'name',
    //     'expertise',
    //     'cert_no',
    //     'nip',
    //     'date_start',
    //     'date_end',
    //     'cert_file',
    //     'cv_file',
    //     'reg_no',
    //     'id_institution',
    //     'membership_status',
    //     'id_lsp',
    //     'email',
    // ];
    protected $guarded = [];

    public function teamMember()
    {
        return $this->hasMany(FormulatorTeamMember::class, 'id_formulator', 'id');
    }

    public function formulatorProvince()
    {
        return $this->belongsTo(Province::class, 'province', 'id');
    }

    public function formulatorDistrict()
    {
        return $this->belongsTo(District::class, 'district', 'id');
    }

    public function getCertFileAttribute()
    {
        if($this->attributes['cert_file']) {
            if(str_contains($this->attributes['cert_file'], 'storage/')) {
                return $this->attributes['cert_file'];
            } else {
                return Storage::url($this->attributes['cert_file']);
            }
        }

        return null;
    }

    public function getCvFileAttribute()
    {
        if($this->attributes['cv_file']) {
            if(str_contains($this->attributes['cv_file'], 'storage/')) {
                return $this->attributes['cv_file'];
            } else {
                return Storage::url($this->attributes['cv_file']);
            }
        }

        return null;
    }
}
