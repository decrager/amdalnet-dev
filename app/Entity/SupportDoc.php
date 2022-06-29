<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SupportDoc extends Model
{
    protected $fillable = ['id_project','name','file'];

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
