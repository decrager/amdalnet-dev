<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowState extends Model
{
    use HasFactory;

    protected $table = 'workflow_states';
    protected $fillable = [ 'state', 'public_tracking', 'code', 'flag' ];
}
