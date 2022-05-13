<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Entity\ArcgisServiceCategory;

class ArcgisService extends Model
{
    use HasFactory;

    protected $table = 'arcgis_service';

    protected $fillable = [
        'id_category', 'name', 'url_service', 'source', 'organization', 'active', 'is_proxy', 'id_province'
    ];

    public function category()
    {
        return $this->belongsTo(ArcgisServiceCategory::class, 'id_category', 'id');
    }
}
