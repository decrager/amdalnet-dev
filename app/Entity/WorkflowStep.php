<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    use HasFactory;
    protected $table = 'workflow_steps';
    protected $fillable = [ 'code', 'doc_type', 'rank', 'is_conditional'];


}
