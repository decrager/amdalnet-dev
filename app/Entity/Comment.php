<?php

namespace App\Entity;

use App\Laravue\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function reply()
    {
        return $this->hasMany(Comment::class, 'reply_to', 'id');
    }

    public function replied()
    {
        return $this->belongsTo(Comment::class, 'reply_to', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function impactIdentification()
    {
        return $this->belongsTo(ImpactIdentification ::class, 'id_impact_identification', 'id');
    }

    public function impactIdentificationClone()
    {
        return $this->belongsTo(ImpactIdentificationClone::class, 'id_impact_identification_clone', 'id');
    }

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class, 'id_stage', 'id');
    }
}
