<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

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

    public function publicConsultation()
    {
        return $this->belongsTo(PublicConsultation::class, 'public_consultation_id', 'id');
    }
}
