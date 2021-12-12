<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AndalComment extends Model
{
    use HasFactory;

    protected $table = 'andal_comments';

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'id_user', 'id');
    }

    public function getUpdatedAtAttribute($value) {
        return date('d F Y H:i:s', strtotime($value));
    }
}
