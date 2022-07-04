<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class PublicConsultation extends Model
{
    protected $table = 'public_consultations';
    protected $fillable = [
        'announcement_id',
        'project_id',
        'event_date',
        'participant',
        'location',
        'address',
        'positive_feedback_summary',
        'negative_feedback_summary',
        'is_publish',
    ];

    public function docs(){
        return $this->hasMany(PublicConsultationDoc::class, 'public_consultation_id', 'id');
    }

    public function getEventDateAttribute($event) {
        return $event ? date('Y-m-d', strtotime($event)) : '';
    }
}
