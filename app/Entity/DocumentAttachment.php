<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DocumentAttachment extends Model
{
    use HasFactory;

    protected $table = 'document_attachment';

    protected $fillable = [
        'id_project', 'attachment', 'type'
    ];

    public function getAttachmentAttribute()
    {
        if($this->attributes['attachment']) {
            if(str_contains($this->attributes['attachment'], 'storage/')) {
                return $this->attributes['attachment'];
            } else {
                return Storage::url($this->attributes['attachment']);
            }
        }

        return null;
    }
}
