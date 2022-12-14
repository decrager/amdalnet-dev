<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveStatus extends Model
{
    use HasFactory;

    protected $table = 'save_status';
    protected $guarded = ['id'];
}