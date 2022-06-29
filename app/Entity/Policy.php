<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Policy extends Model
{
    use HasFactory;

    protected $table = 'policys';

    protected $guarded = [];


    public function regulation()
    {
        return $this->belongsTo(\App\Entity\Regulation::class, 'regulation_type', 'id');
    }

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
