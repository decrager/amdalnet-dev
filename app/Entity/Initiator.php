<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Initiator extends Model
{
    use SoftDeletes;

    // protected $fillable = [
    //     'name',
    //     'pic',
    //     'email',
    //     'phone',
    //     'address',
    //     'user_type',
    //     'nib',
    // ];

    protected $guarded = [];

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
}
