<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMapAttachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_project',
        'attachment_type',
        'file_type',
        'original_filename',
        'stored_filename'
    ];
}
