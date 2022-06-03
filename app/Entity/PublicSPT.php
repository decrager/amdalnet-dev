<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PublicSPT extends Model
{
    use HasFactory;

    protected $table = 'public_spt';

    public function getPhotoAttribute()
    {
        if($this->attributes['photo']) {
            if(str_contains($this->attributes['photo'], 'storage/')) {
                return $this->attributes['photo'];
            } else {
                return Storage::url($this->attributes['photo']);
            }
        }

        return null;
    }

}
