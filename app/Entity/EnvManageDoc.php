<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function getFilepathAttribute()
    {
        if($this->attributes['filepath']) {
            if(str_contains($this->attributes['filepath'], 'storage/')) {
                return $this->attributes['filepath'];
            } else {
                return Storage::url($this->attributes['filepath']);
            }
        }

        return null;
    }
}
