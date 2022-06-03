<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $guarded = [];

    public function getLinkAttribute()
    {
        if($this->attributes['link']) {
            if(str_contains($this->attributes['link'], 'storage/')) {
                return $this->attributes['link'];
            } else {
                return Storage::url($this->attributes['link']);
            }
        }

        return null;
    }
}
