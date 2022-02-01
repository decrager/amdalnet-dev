<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentalApproval extends Model
{
    use HasFactory;

    protected $table = 'environmental_approvals';

    protected $guarded = [];
}
