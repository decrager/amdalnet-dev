<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EnvManagamentSop extends Model
{
    use HasFactory;

    protected $table = 'environmental_managament_sops';

    protected $guarded = [];

    public function getFileAttribute()
    {
        if($this->attributes['file']) {
            if(str_contains($this->attributes['file'], 'storage/')) {
                return $this->attributes['file'];
            } else {
                return Storage::url($this->attributes['file']);
            }
        }

        return null;
    }
}
