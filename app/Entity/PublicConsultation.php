<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class PublicConsultation extends Model
{
    protected $table = 'public_consultations';
    protected $fillable = [
        'announcement_id',
        'event_date',
        'participant',
        'location',
        'address',
        'positive_feedback_summary',
        'negative_feedback_summary',
    ];
}
