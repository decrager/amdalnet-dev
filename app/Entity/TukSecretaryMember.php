<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TukSecretaryMember extends Model
{
    use HasFactory;

    protected $table = 'tuk_secretary_members';

    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id_district', 'id');
    }

    public function feasibilityTestTeam()
    {
        return $this->belongsTo(FeasibilityTestTeam::class, 'id_feasibility_test_team', 'id');
    }

    public function tukProject()
    {
        return $this->hasMany(TukProject::class, 'id_tuk_secretary_member', 'id');
    }

    public function feasibilityTest()
    {
        return $this->hasMany(FeasibilityTest::class, 'id_tuk_secretary_member', 'id');
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
