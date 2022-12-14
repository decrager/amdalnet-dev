<?php

namespace App\Entity;

use App\Laravue\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkspaceComment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'workspace_comment';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
