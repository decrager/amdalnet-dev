<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class ProjectMapAttachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_project',
        'attachment_type',
        'file_type',
        'original_filename',
        'stored_filename',
        'geom',
        'properties',
        'id_styles',
        'step',
    ];

    public function getStoredFilenameAttribute()
    {
        if($this->attributes['stored_filename']) {
            if(str_contains($this->attributes['stored_filename'], 'storage/')) {
                return $this->attributes['stored_filename'];
            } else {
                // return Storage::url($this->attributes['ktr']);
                return Storage::disk('public')->temporaryUrl($this->attributes['stored_filename'], now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')));
            }
        }

        return null;
    }
}
