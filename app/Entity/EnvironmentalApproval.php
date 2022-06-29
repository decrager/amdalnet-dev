<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EnvironmentalApproval extends Model
{
    use HasFactory;

    protected $table = 'environmental_approvals';

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
