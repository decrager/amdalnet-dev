<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantTrait extends Model
{
    use HasFactory;

    protected $table = 'important_traits';

    public function impactAnalysis()
    {
        return $this->hasMany(ImpactAnalysisDetail::class, 'id_important_trait', 'id');
    }
}
