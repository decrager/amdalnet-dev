<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VideoTutorial extends Model
{
    use HasFactory;

    protected $table = 'video_tutorials';

    protected $guarded = [];

    public function getUrlVideoAttribute()
    {
        if($this->attributes['url_video']) {
            if(str_contains($this->attributes['url_video'], 'storage/')) {
                return $this->attributes['url_video'];
            } else {
                return Storage::url($this->attributes['url_video']);
            }
        }

        return null;
    }

}
