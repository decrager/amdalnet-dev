<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeasibilityTest extends Model
{
    use HasFactory;

    public function detail()
    {
        return $this->hasMany(FeasibilityTestDetail::class, 'id_feasibility_test', 'id');
    }
}
