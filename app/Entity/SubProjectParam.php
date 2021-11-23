<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProjectParam extends Model
{
    use HasFactory;

    protected $fillable = [
        'param',
        'scale',
        'scale_unit',
        'result',
        'id_sub_project',
    ];

    public function subProject()
    {
        return $this->belongsTo(SubProject::class, 'id_sub_project');
    }
}
