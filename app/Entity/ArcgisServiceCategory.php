<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Entity\ArcgisService;

class ArcgisServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'arcgis_service_category';

    protected $fillable = [
        'category_name', 'active', 'id_provinsi'
    ];

    public function arcgisServices()
    {
        return $this->hasMany(ArcgisService::class, 'id');
    }
}
