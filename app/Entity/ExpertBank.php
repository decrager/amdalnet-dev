<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ExpertBank extends Model
{
    use SoftDeletes;

    protected $table = 'expert_bank';

    protected $fillable = [
        'name',
        'address',
        'email',
        'mobile_phone_no',
        'expertise',
        'institution',
        'status',
        'cv_file',
        'cert_luk_file',
        'cert_non_luk_file',
        'ijazah_file',
    ];

    public function expertBankTeamMember()
    {
        return $this->hasMany(ExpertBankTeamMember::class, 'id_expert_bank', 'id');
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

    public function getCertLukFileAttribute()
    {
        if($this->attributes['cert_luk_file']) {
            if(str_contains($this->attributes['cert_luk_file'], 'storage/')) {
                return $this->attributes['cert_luk_file'];
            } else {
                return Storage::url($this->attributes['cert_luk_file']);
            }
        }

        return null;
    }

    public function getCertNonLukFileAttribute()
    {
        if($this->attributes['cert_non_luk_file']) {
            if(str_contains($this->attributes['cert_non_luk_file'], 'storage/')) {
                return $this->attributes['cert_non_luk_file'];
            } else {
                return Storage::url($this->attributes['cert_non_luk_file']);
            }
        }

        return null;
    }

    public function getIjazahFileAttribute()
    {
        if($this->attributes['ijazah_file']) {
            if(str_contains($this->attributes['ijazah_file'], 'storage/')) {
                return $this->attributes['ijazah_file'];
            } else {
                return Storage::url($this->attributes['ijazah_file']);
            }
        }

        return null;
    }
}
