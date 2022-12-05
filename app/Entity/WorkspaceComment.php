<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkspaceComment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'workspace_comment';
}
