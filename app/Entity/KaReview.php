<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KaReview extends Model
{
    use HasFactory;

    protected $table = 'ka_reviews';

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }

    public function getApplicationLetterAttribute()
    {
        if($this->attributes['application_letter']) {
            if(str_contains($this->attributes['application_letter'], 'storage/')) {
                return $this->attributes['application_letter'];
            } else {
                return Storage::url($this->attributes['application_letter']);
            }
        }

        return null;
    }
}
