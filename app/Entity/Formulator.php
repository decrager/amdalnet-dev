<?php

namespace App\Entity;

use App\Laravue\Models\User;
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
                // return Storage::url($this->attributes['cert_file']);
                return Storage::disk('public')->temporaryUrl($this->attributes['cert_file'], now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')));
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
                // return Storage::url($this->attributes['cv_file']);
                return Storage::disk('public')->temporaryUrl($this->attributes['cv_file'], now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')));
            }
        }

        return null;
    }

    public function rawCertFile()
    {
        return $this->attributes['cert_file'];
    }

    public function rawCvFile()
    {
        return $this->attributes['cv_file'];
    }

    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }
}
