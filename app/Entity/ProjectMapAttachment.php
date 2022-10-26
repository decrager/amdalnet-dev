<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectMapAttachment extends Model
{
    use HasFactory;
    protected $appends = ['map_file_url'];
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

    public function getMapFileUrlAttribute(){
        if($this->attributes['stored_filename']) {
            if(str_contains($this->attributes['stored_filename'], 'map/')) {
                return $this->attributes['stored_filename'];
            } else {
                // return Storage::url($this->attributes['pre_agreement_file']);
                return Storage::disk('public')->temporaryUrl('map/'.$this->attributes['stored_filename'], now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')));
            }
        }

        return null;
    }
}
