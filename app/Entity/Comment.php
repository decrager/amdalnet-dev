<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function reply()
    {
        return $this->hasOne(Comment::class, 'reply_to', 'id');
    }

    public function replied()
    {
        return $this->belongsTo(Comment::class, 'reply_to', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'id_user', 'id');
    }

    public function impactIdentification()
    {
        return $this->belongsTo(ImpactIdentification::class, 'id_impact_identification', 'id');
    }
}
