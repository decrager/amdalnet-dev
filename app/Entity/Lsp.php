<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lsp extends Model
{
    protected $table = 'lsp';

    protected $guarded = ['id'];

    // public function dataProvince()
    // {
    //     return $this->belongsTo(Province::class, 'province', 'id');
    // }

    // public function dataDistrict()
    // {
    //     return $this->belongsTo(District::class, 'city', 'id');
    // }

    public function members()
    {
        return $this->hasMany(Formulator::class, 'id_lsp', 'id');
    }

    // public function getLspFileAttribute()
    // {
    //     if($this->attributes['lsp_file']) {
    //         if(str_contains($this->attributes['lsp_file'], 'storage/')) {
    //             return $this->attributes['lsp_file'];
    //         } else {
    //             // return Storage::url($this->attributes['cert_file']);
    //             return Storage::disk('public')->temporaryUrl($this->attributes['lsp_file'], now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')));
    //         }
    //     }

    //     return null;
    // }
}
