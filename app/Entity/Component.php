<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Component extends Model
{
    use SoftDeletes;

    protected $table = 'components';
    protected $fillable = [
        'id_project_stage',
        'name',
        'is_master',
        'originator_id'
    ];

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class, 'id_project_stage', 'id');
    }
    public function scopeMaster($query)
    {
        return $query->where('is_master', '=', true);
    }
}
