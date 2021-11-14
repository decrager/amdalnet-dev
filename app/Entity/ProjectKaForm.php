<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectKaForm extends Model
{
    use HasFactory;

    protected $table = 'project_ka_forms';

    public function kaForm()
    {
        return $this->belongsTo(KaForm::class, 'id_ka_form', 'id');
    }
}
