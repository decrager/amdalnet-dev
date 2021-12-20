<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvManageDoc extends Model
{
    use HasFactory;

    protected $table = 'env_manage_docs';

    protected $fillable = [
        'id_project',
        'type',
        'filepath',
    ];

    public function project(){
        return $this->belongsTo(Project::class, 'id_project');
    }
}
