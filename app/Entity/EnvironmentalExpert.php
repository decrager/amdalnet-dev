<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EnvironmentalExpert extends Model
{
    use HasFactory;

    public function teamMembers()
    {
        return $this->hasMany(FormulatorTeamMember::class, 'id_expert', 'id');
    }

    public function getCvAttribute()
    {
        if($this->attributes['cv']) {
            if(str_contains($this->attributes['cv'], 'storage/')) {
                return $this->attributes[cv];
            } else {
                return Storage::url($this->attributes['cv']);
            }
        }

        return null;
    }
}
