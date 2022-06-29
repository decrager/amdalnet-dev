<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class WorkflowLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_project', 'transition', 'from_place', 'to_place', 'duration', 'duration_total', 'created_by', 'updated_by'
    ];

    /// relations
    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }
}
