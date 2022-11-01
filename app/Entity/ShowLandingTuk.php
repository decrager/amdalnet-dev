<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowLandingTuk extends Model
{
    use HasFactory;

    public $table = "showlandingtuk";
    // public function showNameTuk(){
    //     return (CONCAT('Tim Uji Kelayakan '.$this->authority));
    // }
}
