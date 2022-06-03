<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LukMember extends Model
{
    use HasFactory;

    protected $table = 'luk_members';

    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id_district', 'id');
    }

    public function feasibilityTestTeamMember()
    {
        return $this->hasOne(FeasibilityTestTeamMember::class, 'id_luk_member', 'id');
    }

    public function getCvAttribute()
    {
        if($this->attributes['cv']) {
            if(str_contains($this->attributes['cv'], 'storage/')) {
                return $this->attributes['cv'];
            } else {
                return Storage::url($this->attributes['cv']);
            }
        }

        return null;
    }
}
