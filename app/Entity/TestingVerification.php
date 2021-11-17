<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestingVerification extends Model
{
    use HasFactory;

    protected $table = 'testing_verifications';

    public function forms()
    {
        return $this->hasMany(ProjectKaForm::class, 'id_testing_verification', 'id');
    }
}
