<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PublicConsultationDoc extends Model
{
    // doc_json:
    // - doc_type: Foto Dokumentasi, Berita Acara, Daftar Hadir, Pengumuman
    // - original_filename
    // - file_extension
    // - filepath
    // - uploaded_by
    protected $fillable = [
        'public_consultation_id',
        'doc_json',
    ];

    protected $casts = [
        'doc_json' => 'array',
    ];

    protected $appends = ['file'];

    public function publicConsultation()
    {
        return $this->belongsTo(PublicConsultation::class, 'public_consultation_id', 'id');
    }

    public function getFileAttribute()
    {
        $doc_json = $this->attributes['doc_json'];
        if($doc_json) {
            $file = json_decode(json_decode($doc_json, true),true)['filepath'];
            // return Storage::url($file);
            return Storage::disk('public')->temporaryUrl($file, now()->addMinutes(env('TEMPORARY_URL_TIMEOUT')));
        }

        return null;
    }
}
