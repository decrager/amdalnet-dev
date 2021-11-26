<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAttachment extends Model
{
    use HasFactory;

    protected $table = 'document_attachment';

    protected $fillable = [
        'id_project', 'attachment', 'type'
    ];
}
