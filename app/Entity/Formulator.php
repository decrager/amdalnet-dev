<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Formulator extends Model
{
    protected $fillable = [
        'id_formulator',
        'name',
        'expertise',
        'cert_no',
        'nip',
        'date_start',
        'date_end',
        'cert_file',
        'cv_file',
        'reg_no',
        'id_institution',
        'membership_status',
        'id_lsp',
    ];
}
