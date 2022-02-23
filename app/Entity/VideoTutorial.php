<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTutorial extends Model
{
    use HasFactory;

    protected $table = 'video_tutorials';

    protected $guarded = [];

}
