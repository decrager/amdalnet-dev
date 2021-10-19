<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $fillable = [
        'announcement_id',
        'name',
        'id_card_number',
        'phone',
        'email',
        'suggestion',
        'concern',
        'expectation',
        'photo_filepath',
        'responder_type_id',
        'is_relevant',
        'set_relevant_by',
        'set_relevant_at',
        'deleted',
        'deleted_at',
    ];
}
