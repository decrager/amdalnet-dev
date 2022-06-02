<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FeasibilityTestTeam extends Model
{
    use HasFactory;

    protected $table = 'feasibility_test_teams';

    public function member()
    {
        return $this->hasMany(FeasibilityTestTeamMember::class, 'id_feasibility_test_team', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id_district', 'id');
    }

    public function provinceAuthority()
    {
        return $this->belongsTo(Province::class, 'id_province_name', 'id');
    }

    public function districtAuthority()
    {
        return $this->belongsTo(District::class, 'id_district_name', 'id');
    }

    public function secretary()
    {
        return $this->hasMany(TukSecretaryMember ::class, 'id_feasibility_test_team', 'id');
    }

    public function getLogoAttribute()
    {
        if($this->attributes['logo']) {
            if(str_contains($this->attributes['logo'], 'storage/')) {
                return $this->attributes['logo'];
            } else {
                return Storage::url($this->attributes['logo']);
            }
        }

        return null;
    }

    public function getAssignmentFileAttribute()
    {
        if($this->attributes['assignment_file']) {
            if(str_contains($this->attributes['assignment_file'], 'storage/')) {
                return $this->attributes['assignment_file'];
            } else {
                return Storage::url($this->attributes['assignment_file']);
            }
        }

        return null;
    }
}
